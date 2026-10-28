<?php
/**
 * Funções do app: estado na sessão, geração do cardápio e utilidades.
 */

require_once __DIR__ . '/dados.php';

date_default_timezone_set('America/Sao_Paulo');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/** Escapa texto para colocar no HTML com segurança. */
function e($texto): string
{
    return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
}

/** Preferências padrão (as do protótipo). */
function prefs_padrao(): array
{
    return [
        'objetivo'   => 'emagrecer',
        'restricoes' => [],
        'nao_gosto'  => ['Berinjela', 'Fígado'],
        'refeicoes'  => [
            'cafe'   => ['on' => true, 'tempo' => 10],
            'almoco' => ['on' => true, 'tempo' => 30],
            'lanche' => ['on' => true, 'tempo' => 5],
            'jantar' => ['on' => true, 'tempo' => 15],
        ],
    ];
}

/** Segunda-feira da semana atual, no formato AAAA-MM-DD. */
function semana_inicio(): DateTimeImmutable
{
    $hoje = new DateTimeImmutable('today');
    $diaSemana = (int) $hoje->format('N'); // 1 = segunda ... 7 = domingo
    return $hoje->modify('-' . ($diaSemana - 1) . ' days');
}

/** Garante que a sessão tem tudo o que o app precisa. Zera o progresso quando a semana vira. */
function iniciar_estado(): void
{
    $_SESSION['prefs']  = $_SESSION['prefs'] ?? prefs_padrao();
    $_SESSION['gerado'] = $_SESSION['gerado'] ?? false;

    $semana = semana_inicio()->format('Y-m-d');
    if (($_SESSION['semana'] ?? null) !== $semana) {
        $_SESSION['semana'] = $semana;
        $_SESSION['feitos'] = [];
        $_SESSION['trocas'] = [];
    }
}

function prefs(): array
{
    return $_SESSION['prefs'];
}

/** Os 7 dias da semana atual. */
function dias_semana(): array
{
    $curtos = ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'];
    $longos = ['Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado', 'Domingo'];
    $inicio = semana_inicio();
    $hoje = (new DateTimeImmutable('today'))->format('Y-m-d');

    $dias = [];
    for ($i = 0; $i < 7; $i++) {
        $data = $inicio->modify("+$i days");
        $dias[] = [
            'indice' => $i,
            'curto'  => $curtos[$i],
            'longo'  => $longos[$i],
            'num'    => (int) $data->format('j'),
            'mes'    => (int) $data->format('n'),
            'hoje'   => $data->format('Y-m-d') === $hoje,
        ];
    }
    return $dias;
}

function indice_hoje(): int
{
    return (int) (new DateTimeImmutable('today'))->format('N') - 1;
}

/** Ex.: "26 out – 1 nov" */
function periodo_semana(): string
{
    $meses = [1 => 'jan', 'fev', 'mar', 'abr', 'mai', 'jun', 'jul', 'ago', 'set', 'out', 'nov', 'dez'];
    $dias = dias_semana();
    $ini = $dias[0];
    $fim = $dias[6];
    if ($ini['mes'] === $fim['mes']) {
        return $ini['num'] . ' a ' . $fim['num'] . ' de ' . $meses[$fim['mes']];
    }
    return $ini['num'] . ' ' . $meses[$ini['mes']] . ' – ' . $fim['num'] . ' ' . $meses[$fim['mes']];
}

/** Minúsculas e sem acento, para comparar "fígado" com "Figado". */
function normalizar(string $texto): string
{
    $texto = mb_strtolower(trim($texto), 'UTF-8');
    return strtr($texto, [
        'á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a', 'é' => 'e', 'ê' => 'e', 'í' => 'i',
        'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ú' => 'u', 'ü' => 'u', 'ç' => 'c',
    ]);
}

/** O prato respeita as restrições, o "não gosto" e (opcionalmente) o tempo da refeição? */
function prato_permitido(array $prato, array $prefs, bool $checarTempo): bool
{
    foreach ($prefs['restricoes'] as $r) {
        if (in_array(RESTRICOES[$r]['tag'], $prato['tags'], true)) {
            return false;
        }
    }

    $textos = [normalizar($prato['nome'])];
    foreach ($prato['ingredientes'] as $ing) {
        $textos[] = normalizar($ing[0]);
    }
    foreach ($prefs['nao_gosto'] as $palavra) {
        $p = normalizar($palavra);
        if ($p === '') {
            continue;
        }
        foreach ($textos as $t) {
            if (str_contains($t, $p)) {
                return false;
            }
        }
    }

    if ($checarTempo) {
        $limite = $prefs['refeicoes'][$prato['tipo']]['tempo'];
        if ($limite < 45 && $prato['tempo'] > $limite) {
            return false;
        }
    }
    return true;
}

/**
 * Lista ordenada de pratos possíveis para um tipo de refeição.
 * Primeiro os do cardápio base (na ordem dos dias), depois as outras ideias.
 * Se o limite de tempo deixar a lista vazia, ele é ignorado.
 */
function opcoes_para(string $tipo): array
{
    global $PRATOS;
    $prefs = prefs();

    $ordem = array_column(CARDAPIO_BASE, $tipo);
    foreach ($PRATOS as $id => $p) {
        if ($p['tipo'] === $tipo && !in_array($id, $ordem, true)) {
            $ordem[] = $id;
        }
    }

    $comTempo = array_values(array_filter($ordem, fn($id) => prato_permitido($PRATOS[$id], $prefs, true)));
    if ($comTempo) {
        return $comTempo;
    }
    return array_values(array_filter($ordem, fn($id) => prato_permitido($PRATOS[$id], $prefs, false)));
}

/** Prato escolhido para um dia e refeição, já considerando as trocas. Retorna o id ou null. */
function prato_do_dia(int $dia, string $tipo): ?string
{
    $opcoes = opcoes_para($tipo);
    if (!$opcoes) {
        return null;
    }
    $base = CARDAPIO_BASE[$dia][$tipo];
    $pos = array_search($base, $opcoes, true);
    if ($pos === false) {
        $pos = $dia;
    }
    $troca = $_SESSION['trocas']["$dia-$tipo"] ?? 0;
    return $opcoes[($pos + $troca) % count($opcoes)];
}

/** Quantas ideias existem para essa refeição (para mostrar "ideia 2 de 5"). */
function total_ideias(string $tipo): int
{
    return count(opcoes_para($tipo));
}

/** Tipos de refeição que estão ligados nas preferências. */
function refeicoes_ativas(): array
{
    return array_keys(array_filter(prefs()['refeicoes'], fn($r) => $r['on']));
}

function esta_feito(int $dia, string $tipo): bool
{
    return !empty($_SESSION['feitos']["$dia-$tipo"]);
}

function total_feitos(): int
{
    $ativas = refeicoes_ativas();
    $n = 0;
    for ($d = 0; $d < 7; $d++) {
        foreach ($ativas as $t) {
            if (esta_feito($d, $t)) {
                $n++;
            }
        }
    }
    return $n;
}

/** Dias seguidos (até hoje) com pelo menos uma refeição feita em casa. */
function dias_seguidos(): int
{
    $ativas = refeicoes_ativas();
    $temAlgo = function (int $d) use ($ativas) {
        foreach ($ativas as $t) {
            if (esta_feito($d, $t)) {
                return true;
            }
        }
        return false;
    };

    $d = indice_hoje();
    if (!$temAlgo($d)) {
        $d--; // hoje ainda está em andamento: não quebra a sequência
    }
    $n = 0;
    while ($d >= 0 && $temAlgo($d)) {
        $n++;
        $d--;
    }
    return $n;
}

/** Formata uma quantidade: 0.5 un → "½ unidade", 450 g → "450 g", 1500 ml → "1,5 l". */
function formatar_qtd(?float $qtd, ?string $unidade, float $fator = 1): string
{
    if ($qtd === null || $unidade === null) {
        return 'a gosto';
    }
    $n = $qtd * $fator;

    if ($unidade === 'g' || $unidade === 'ml') {
        if ($unidade === 'ml' && $n >= 1000) {
            return str_replace('.', ',', (string) round($n / 1000, 1)) . ' l';
        }
        $passo = $n < 50 ? 5 : 10;
        return (string) max($passo, round($n / $passo) * $passo) . ' ' . $unidade;
    }

    $r = round($n * 4) / 4;
    if ($r <= 0) {
        $r = 0.25;
    }
    $inteiro = (int) floor($r);
    $fracoes = ['0' => '', '0.25' => '¼', '0.5' => '½', '0.75' => '¾'];
    $frac = $fracoes[(string) ($r - $inteiro)] ?? '';
    $num = $inteiro === 0 ? $frac : $inteiro . $frac;

    [$sing, $plural] = UNIDADES[$unidade];
    return $num . ' ' . ($r > 1 ? $plural : $sing);
}

function porcoes_texto(int $n): string
{
    return $n === 1 ? '1 porção' : "$n porções";
}

/** Ícones em SVG (traço na cor do texto). */
function icone(string $nome, int $tam = 24): string
{
    $paths = [
        'cafe'    => '<path d="M4 9h13v5a5 5 0 0 1-5 5H9a5 5 0 0 1-5-5z"/><path d="M17 11h1.5a2.5 2.5 0 0 1 0 5H17"/><path d="M8 3.5c0 1-1 1.5-1 2.5M12 3.5c0 1-1 1.5-1 2.5"/>',
        'almoco'  => '<circle cx="12.5" cy="12" r="6"/><circle cx="12.5" cy="12" r="2.8"/><path d="M3 4v4.5a1.5 1.5 0 0 0 3 0V4M4.5 4v16"/><path d="M21.5 4c-1.4.9-2 2.8-2 5.5h2V20"/>',
        'lanche'  => '<path d="M12 7.5c-2-2-7-1.5-7 4 0 4 3 8.5 5 8.5 1 0 1.3-.6 2-.6s1 .6 2 .6c2 0 5-4.5 5-8.5 0-5.5-5-6-7-4z"/><path d="M12 7.5c0-2 1-3.5 3-4.5"/>',
        'jantar'  => '<path d="M3 11.5h18a9 8 0 0 1-18 0z"/><path d="M8 20h8"/><path d="M9.5 8c0-1.2 1.2-1.6 1.2-3M13.5 8c0-1.2 1.2-1.6 1.2-3"/>',
        'seta'    => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'voltar'  => '<path d="M15 5l-7 7 7 7"/>',
        'check'   => '<path d="M5 12.5l4.5 4.5L19 7.5"/>',
        'relogio' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'trocar'  => '<path d="M3 7h4l10 10h4M3 17h4l3-3M14 10l3-3h4"/><path d="M19 5l2 2-2 2M19 15l2 2-2 2"/>',
        'ajustes' => '<path d="M4 7h10M18 7h2M4 17h4M12 17h8"/><circle cx="16" cy="7" r="2"/><circle cx="10" cy="17" r="2"/>',
        'ideia'   => '<path d="M9 18h6M10 21h4M12 3a6 6 0 0 0-3.6 10.8c.6.5 1 1.2 1 2V16h5.2v-.2c0-.8.4-1.5 1-2A6 6 0 0 0 12 3z"/>',
        'mais'    => '<path d="M12 5v14M5 12h14"/>',
        'menos'   => '<path d="M5 12h14"/>',
    ];
    if ($nome === 'fogo') {
        return '<svg aria-hidden="true" width="' . $tam . '" height="' . $tam . '" viewBox="0 0 24 24" fill="currentColor"><path d="M12 21c-3.9 0-6.5-2.6-6.5-6 0-4 3.5-5.5 3.5-9 2.5 1.5 4 3.5 4 5.5.8-.7 1.3-1.8 1.4-3 2.2 1.7 4.1 4 4.1 6.5 0 3.4-2.6 6-6.5 6z"/></svg>';
    }
    return '<svg aria-hidden="true" width="' . $tam . '" height="' . $tam . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . ($paths[$nome] ?? '') . '</svg>';
}

/** Redireciona e encerra. */
function ir_para(string $url): void
{
    header('Location: ' . $url);
    exit;
}

iniciar_estado();

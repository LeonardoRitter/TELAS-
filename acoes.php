<?php
/**
 * Recebe os formulários (POST), atualiza a sessão e volta para a página certa.
 */
require_once __DIR__ . '/includes/funcoes.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ir_para('index.php');
}

$acao = $_POST['acao'] ?? '';
$dia  = max(0, min(6, (int) ($_POST['dia'] ?? 0)));
$tipo = $_POST['tipo'] ?? '';
$tipoValido = array_key_exists($tipo, TIPOS);

// Só aceita voltar para páginas do próprio app.
$voltar = $_POST['voltar'] ?? 'semana.php';
if (!preg_match('/^(semana|receita)\.php(\?[a-z0-9=&_-]*)?$/i', $voltar)) {
    $voltar = 'semana.php';
}

switch ($acao) {
    case 'salvar_prefs':
        $prefs = prefs_padrao();

        $obj = $_POST['objetivo'] ?? '';
        $prefs['objetivo'] = array_key_exists($obj, OBJETIVOS) ? $obj : 'melhor';

        $restricoes = (array) ($_POST['restricoes'] ?? []);
        $prefs['restricoes'] = array_values(array_intersect(array_keys(RESTRICOES), $restricoes));

        $naoGosto = [];
        foreach (explode(',', (string) ($_POST['nao_gosto'] ?? '')) as $item) {
            $item = trim(mb_substr($item, 0, 40));
            if ($item !== '' && count($naoGosto) < 15) {
                $naoGosto[] = mb_strtoupper(mb_substr($item, 0, 1)) . mb_strtolower(mb_substr($item, 1));
            }
        }
        $prefs['nao_gosto'] = array_values(array_unique($naoGosto));

        foreach (TIPOS as $id => $t) {
            $tempo = (int) ($_POST['tempo'][$id] ?? 0);
            $prefs['refeicoes'][$id] = [
                'on'    => isset($_POST['ativa'][$id]),
                'tempo' => in_array($tempo, $t['opcoes'], true) ? $tempo : $t['opcoes'][1],
            ];
        }

        if (!refeicoes_ativas_de($prefs)) {
            $_SESSION['prefs'] = $prefs;
            $_SESSION['erro'] = 'Escolha pelo menos uma refeição pra gente montar o cardápio.';
            ir_para('preferencias.php');
        }

        $_SESSION['prefs']  = $prefs;
        $_SESSION['gerado'] = true;
        $_SESSION['trocas'] = []; // novas preferências, cardápio novo
        ir_para('semana.php?dia=' . indice_hoje());

    case 'trocar':
        if ($tipoValido) {
            $_SESSION['trocas']["$dia-$tipo"] = ($_SESSION['trocas']["$dia-$tipo"] ?? 0) + 1;
            unset($_SESSION['feitos']["$dia-$tipo"]);
        }
        ir_para($voltar);

    case 'feito':
        if ($tipoValido) {
            $chave = "$dia-$tipo";
            if (empty($_SESSION['feitos'][$chave])) {
                $_SESSION['feitos'][$chave] = true;
            } else {
                unset($_SESSION['feitos'][$chave]);
            }
        }
        ir_para($voltar);

    case 'recomecar':
        session_destroy();
        ir_para('preferencias.php');
}

ir_para('index.php');

function refeicoes_ativas_de(array $prefs): array
{
    return array_keys(array_filter($prefs['refeicoes'], fn($r) => $r['on']));
}

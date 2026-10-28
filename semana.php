<?php
require_once __DIR__ . '/includes/funcoes.php';

if (!$_SESSION['gerado']) {
    ir_para('preferencias.php');
}

$prefs  = prefs();
$dias   = dias_semana();
$diaSel = isset($_GET['dia']) ? max(0, min(6, (int) $_GET['dia'])) : indice_hoje();
$ativas = refeicoes_ativas();

$total  = count($ativas) * 7;
$feitos = total_feitos();
$dash   = $total ? round($feitos / $total * 163.4, 1) : 0; // 163.4 = perímetro do anel (2·π·26)
$seguidos = dias_seguidos();

$feitosHoje = count(array_filter($ativas, fn($t) => esta_feito($diaSel, $t)));
$voltar = 'semana.php?dia=' . $diaSel;

// Dica do dia: aproveita o almoço que rende mais de uma porção.
$dica = 'separe os ingredientes de amanhã hoje à noite. Economiza uns bons minutos.';
if (in_array('almoco', $ativas, true) && ($idAlmoco = prato_do_dia($diaSel, 'almoco'))) {
    $porc = $PRATOS[$idAlmoco]['porcoes'];
    if ($porc >= 2) {
        $dica = 'o almoço rende ' . porcoes_texto($porc) . '. Guarde uma para o dia seguinte.';
    }
}

$titulo = 'Sua semana';
require __DIR__ . '/includes/topo.php';
?>
<div class="bolha bolha--menta" aria-hidden="true"></div>

<div class="tela">
    <header class="cabecalho cabecalho--linha">
        <div>
            <span class="sub">Olá!</span>
            <h1 class="titulo-grande">Sua semana</h1>
            <span class="sub"><?= e(periodo_semana()) ?></span>
        </div>
        <a class="botao-icone" href="preferencias.php" aria-label="Preferências"><?= icone('ajustes', 22) ?></a>
    </header>

    <div class="pills">
        <span class="pill pill--verde">Objetivo: <?= e(mb_strtolower(OBJETIVOS[$prefs['objetivo']]['nome'])) ?></span>
        <span class="pill"><?= count($ativas) ?> <?= count($ativas) === 1 ? 'refeição' : 'refeições' ?> por dia</span>
        <?php foreach ($prefs['restricoes'] as $r): ?>
            <span class="pill"><?= e(RESTRICOES[$r]['nome']) ?></span>
        <?php endforeach; ?>
    </div>

    <div class="grade-2">
        <div class="placar placar--escuro">
            <svg role="img" aria-label="<?= $feitos ?> de <?= $total ?> refeições feitas" width="58" height="58" viewBox="0 0 64 64">
                <circle cx="32" cy="32" r="26" fill="none" stroke="#3A4A40" stroke-width="8"/>
                <circle cx="32" cy="32" r="26" fill="none" stroke="#FFD45C" stroke-width="8" stroke-linecap="round"
                        transform="rotate(-90 32 32)" stroke-dasharray="<?= $dash ?> 200"/>
            </svg>
            <div>
                <span class="placar__num"><?= $feitos ?><small>/<?= $total ?></small></span>
                <span class="placar__rot">pratos feitos</span>
            </div>
        </div>
        <div class="placar placar--laranja">
            <span class="placar__icone"><?= icone('fogo', 24) ?></span>
            <div>
                <span class="placar__num"><?= $seguidos ?> <?= $seguidos === 1 ? 'dia' : 'dias' ?></span>
                <span class="placar__rot">seguidos cozinhando</span>
            </div>
        </div>
    </div>

    <nav class="dias" aria-label="Dias da semana">
        <?php foreach ($dias as $d):
            $completo = $ativas && count(array_filter($ativas, fn($t) => esta_feito($d['indice'], $t))) === count($ativas);
            $classes = 'dia' . ($d['indice'] === $diaSel ? ' dia--ativo' : '') . ($completo ? ' dia--completo' : '') . ($d['hoje'] ? ' dia--hoje' : '');
        ?>
            <a class="<?= $classes ?>" href="semana.php?dia=<?= $d['indice'] ?>" aria-label="<?= e($d['longo'] . ' ' . $d['num']) ?>"<?= $d['indice'] === $diaSel ? ' aria-current="date"' : '' ?>>
                <span class="dia__curto"><?= e($d['curto']) ?></span>
                <span class="dia__num"><?= $d['num'] ?></span>
                <span class="dia__ponto"></span>
            </a>
        <?php endforeach; ?>
    </nav>

    <div class="linha-titulo">
        <h2 class="titulo-medio"><?= e($dias[$diaSel]['longo']) ?><?= $dias[$diaSel]['hoje'] ? ' · hoje' : '' ?></h2>
        <span class="status"><?= $feitosHoje === count($ativas) ? 'Dia completo!' : $feitosHoje . ' de ' . count($ativas) . ' feitos' ?></span>
    </div>

    <?php foreach ($ativas as $tipo):
        $id = prato_do_dia($diaSel, $tipo);
        $feito = esta_feito($diaSel, $tipo);
        $troca = $_SESSION['trocas']["$diaSel-$tipo"] ?? 0;
    ?>
        <article class="refeicao-card<?= $feito ? ' refeicao-card--feito' : '' ?>">
            <?php if ($id === null): ?>
                <p class="vazio"><strong><?= e(TIPOS[$tipo]['nome']) ?>:</strong> nenhum prato combina com essas restrições. Que tal ajustar as preferências?</p>
            <?php else: $p = $PRATOS[$id]; ?>
                <div class="refeicao-card__topo">
                    <span class="tile tile--grande tile--<?= e($tipo) ?>">
                        <?= icone($tipo, 34) ?>
                        <?php if ($feito): ?><span class="selo-feito"><?= icone('check', 13) ?></span><?php endif; ?>
                    </span>
                    <div class="refeicao-card__info">
                        <div class="refeicao-card__tag">
                            <span class="tag tag--<?= e($tipo) ?>"><?= e(TIPOS[$tipo]['tag']) ?></span>
                            <?php if ($troca > 0): ?><span class="selo-ideia">ideia <?= ($troca % total_ideias($tipo)) + 1 ?> de <?= total_ideias($tipo) ?></span><?php endif; ?>
                        </div>
                        <h3 class="refeicao-card__nome"><?= e($p['nome']) ?></h3>
                        <div class="metas">
                            <span class="meta"><?= icone('relogio', 13) ?><?= $p['tempo'] ?> min</span>
                            <span class="meta"><?= porcoes_texto($p['porcoes']) ?></span>
                            <span class="meta"><?= e($p['nivel']) ?></span>
                        </div>
                    </div>
                </div>
                <div class="refeicao-card__acoes">
                    <a class="botao botao--verde" href="receita.php?dia=<?= $diaSel ?>&amp;tipo=<?= e($tipo) ?>">Ver receita</a>
                    <form method="post" action="acoes.php">
                        <input type="hidden" name="acao" value="trocar">
                        <input type="hidden" name="dia" value="<?= $diaSel ?>">
                        <input type="hidden" name="tipo" value="<?= e($tipo) ?>">
                        <input type="hidden" name="voltar" value="<?= e($voltar) ?>">
                        <button type="submit" class="botao botao--claro"><?= icone('trocar', 17) ?>Outra ideia</button>
                    </form>
                    <form method="post" action="acoes.php">
                        <input type="hidden" name="acao" value="feito">
                        <input type="hidden" name="dia" value="<?= $diaSel ?>">
                        <input type="hidden" name="tipo" value="<?= e($tipo) ?>">
                        <input type="hidden" name="voltar" value="<?= e($voltar) ?>">
                        <button type="submit" class="botao-check" aria-pressed="<?= $feito ? 'true' : 'false' ?>" aria-label="Marcar <?= e(mb_strtolower(TIPOS[$tipo]['nome'])) ?> como feito"><?= icone('check', 20) ?></button>
                    </form>
                </div>
            <?php endif; ?>
        </article>
    <?php endforeach; ?>

    <div class="dica">
        <span class="dica__icone"><?= icone('ideia', 20) ?></span>
        <p><strong>Dica:</strong> <?= e($dica) ?></p>
    </div>
</div>
<?php require __DIR__ . '/includes/rodape.php'; ?>

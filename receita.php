<?php
require_once __DIR__ . '/includes/funcoes.php';

if (!$_SESSION['gerado']) {
    ir_para('preferencias.php');
}

$dias = dias_semana();
$dia  = max(0, min(6, (int) ($_GET['dia'] ?? indice_hoje())));
$tipo = $_GET['tipo'] ?? 'almoco';
if (!array_key_exists($tipo, TIPOS)) {
    $tipo = 'almoco';
}

$id = prato_do_dia($dia, $tipo);
if ($id === null) {
    ir_para('semana.php?dia=' . $dia);
}
$p = $PRATOS[$id];

$porcoes = max(1, min(8, (int) ($_GET['porcoes'] ?? $p['porcoes'])));
$fator   = $porcoes / $p['porcoes'];
$feito   = esta_feito($dia, $tipo);

$base   = 'receita.php?dia=' . $dia . '&tipo=' . $tipo;
$voltar = $base . '&porcoes=' . $porcoes;

$titulo = $p['nome'];
require __DIR__ . '/includes/topo.php';
?>
<div class="hero hero--<?= e($tipo) ?>">
    <div class="hero__bolha" aria-hidden="true"></div>
    <div class="hero__ponto" aria-hidden="true"></div>
    <div class="hero__prato" aria-hidden="true"><?= icone($tipo, 96) ?></div>
    <a class="botao-voltar" href="semana.php?dia=<?= $dia ?>"><?= icone('voltar', 20) ?>Semana</a>
</div>

<div class="folha">
    <div class="cabecalho">
        <span class="tag tag--<?= e($tipo) ?>"><?= e(TIPOS[$tipo]['tag']) ?> · <?= e(mb_strtoupper($dias[$dia]['curto'])) ?></span>
        <h1 class="titulo-receita"><?= e($p['nome']) ?></h1>
    </div>

    <div class="grade-3">
        <div class="stat"><strong><?= $p['tempo'] ?> min</strong><span>Preparo</span></div>
        <div class="stat"><strong><?= $porcoes ?></strong><span><?= $porcoes === 1 ? 'Porção' : 'Porções' ?></span></div>
        <div class="stat"><strong><?= e($p['nivel']) ?></strong><span>Nível</span></div>
    </div>

    <div class="porcoes">
        <span>Pra quantas porções?</span>
        <div class="porcoes__controle">
            <a class="porcoes__btn porcoes__btn--menos<?= $porcoes <= 1 ? ' desativado' : '' ?>" href="<?= e($base . '&porcoes=' . max(1, $porcoes - 1)) ?>" aria-label="Menos uma porção"><?= icone('menos', 18) ?></a>
            <span class="porcoes__num"><?= $porcoes ?></span>
            <a class="porcoes__btn porcoes__btn--mais<?= $porcoes >= 8 ? ' desativado' : '' ?>" href="<?= e($base . '&porcoes=' . min(8, $porcoes + 1)) ?>" aria-label="Mais uma porção"><?= icone('mais', 18) ?></a>
        </div>
    </div>

    <section class="bloco">
        <div class="linha-titulo">
            <h2 class="titulo-medio">Ingredientes</h2>
            <span class="status">toque para ir separando</span>
        </div>
        <ul class="ingredientes">
            <?php foreach ($p['ingredientes'] as $i => $ing): ?>
                <li>
                    <label class="ingrediente">
                        <input type="checkbox">
                        <span class="caixa" aria-hidden="true"><?= icone('check', 14) ?></span>
                        <span class="ingrediente__nome"><?= e($ing[0]) ?></span>
                        <span class="ingrediente__qtd"><?= e(formatar_qtd($ing[1] === null ? null : (float) $ing[1], $ing[2], $fator)) ?></span>
                    </label>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>

    <section class="bloco">
        <div class="linha-titulo">
            <h2 class="titulo-medio">Modo de preparo</h2>
            <span class="status">marque cada passo</span>
        </div>
        <ol class="passos">
            <?php foreach ($p['passos'] as $n => $texto): ?>
                <li>
                    <label class="passo">
                        <input type="checkbox">
                        <span class="passo__num"><?= $n + 1 ?></span>
                        <span class="passo__texto"><?= e($texto) ?></span>
                    </label>
                </li>
            <?php endforeach; ?>
        </ol>
    </section>

    <p class="nota">Quantidades sugeridas pela receita. Para uma dieta específica, consulte um nutricionista.</p>

    <?php if (!$feito): ?>
        <div class="grade-2">
            <form method="post" action="acoes.php">
                <input type="hidden" name="acao" value="trocar">
                <input type="hidden" name="dia" value="<?= $dia ?>">
                <input type="hidden" name="tipo" value="<?= e($tipo) ?>">
                <input type="hidden" name="voltar" value="<?= e($base) ?>">
                <button type="submit" class="botao botao--claro botao--alto">Trocar prato</button>
            </form>
            <form method="post" action="acoes.php">
                <input type="hidden" name="acao" value="feito">
                <input type="hidden" name="dia" value="<?= $dia ?>">
                <input type="hidden" name="tipo" value="<?= e($tipo) ?>">
                <input type="hidden" name="voltar" value="<?= e($voltar) ?>">
                <button type="submit" class="botao botao--verde botao--alto">Marcar como feito</button>
            </form>
        </div>
    <?php else: ?>
        <div class="parabens" role="status">
            <span class="confete confete--1" aria-hidden="true"></span>
            <span class="confete confete--2" aria-hidden="true"></span>
            <span class="confete confete--3" aria-hidden="true"></span>
            <strong class="parabens__titulo">Mandou bem!</strong>
            <span class="parabens__texto">Mais um prato feito em casa. Sua semana está em <?= total_feitos() ?> de <?= count(refeicoes_ativas()) * 7 ?>.</span>
            <div class="parabens__acoes">
                <a class="botao botao--amarelo" href="semana.php?dia=<?= $dia ?>">Voltar pra semana</a>
                <form method="post" action="acoes.php">
                    <input type="hidden" name="acao" value="feito">
                    <input type="hidden" name="dia" value="<?= $dia ?>">
                    <input type="hidden" name="tipo" value="<?= e($tipo) ?>">
                    <input type="hidden" name="voltar" value="<?= e($voltar) ?>">
                    <button type="submit" class="botao botao--contorno">Desfazer</button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/rodape.php'; ?>

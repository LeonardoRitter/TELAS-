<?php
require_once __DIR__ . '/includes/funcoes.php';

$prefs = prefs();
$erro = $_SESSION['erro'] ?? null;
unset($_SESSION['erro']);

$titulo = 'Preferências';
require __DIR__ . '/includes/topo.php';
?>
<div class="bolha bolha--amarela" aria-hidden="true"></div>
<div class="bolha bolha--laranja" aria-hidden="true"></div>

<div class="tela">
    <div class="progresso-passos">
        <div class="barras"><span class="ativa"></span><span></span><span></span></div>
        <span class="eyebrow">PASSO 1 DE 3 · PREFERÊNCIAS</span>
    </div>

    <header class="cabecalho">
        <h1 class="titulo-grande">Bora montar sua semana?</h1>
        <p class="sub">Do café ao jantar. Leva 1 minuto, prometo.</p>
    </header>

    <?php if ($erro): ?>
        <p class="alerta" role="alert"><?= e($erro) ?></p>
    <?php endif; ?>

    <form method="post" action="acoes.php" class="form-prefs">
        <input type="hidden" name="acao" value="salvar_prefs">

        <fieldset class="cartao">
            <legend class="secao"><span class="num num--verde">1</span>Seu objetivo</legend>
            <div class="grade-2">
                <?php foreach (OBJETIVOS as $id => $o): ?>
                    <label class="opcao">
                        <input type="radio" name="objetivo" value="<?= e($id) ?>" <?= $prefs['objetivo'] === $id ? 'checked' : '' ?>>
                        <span class="opcao__nome"><?= e($o['nome']) ?></span>
                        <span class="opcao__desc"><?= e($o['desc']) ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
            <p class="nota">O objetivo ajuda a escolher os pratos. Para metas de peso, o ideal é ter um nutricionista junto.</p>
        </fieldset>

        <fieldset class="cartao">
            <legend class="secao"><span class="num num--pessego">2</span>Suas refeições do dia</legend>
            <?php foreach (TIPOS as $id => $t): $r = $prefs['refeicoes'][$id]; ?>
                <div class="refeicao refeicao--<?= e($id) ?>">
                    <div class="refeicao__topo">
                        <span class="tile tile--<?= e($id) ?>"><?= icone($id) ?></span>
                        <span class="refeicao__nome"><?= e($t['nome']) ?></span>
                        <label class="switch">
                            <input type="checkbox" name="ativa[<?= e($id) ?>]" value="1" <?= $r['on'] ? 'checked' : '' ?>>
                            <span class="switch__trilho" aria-hidden="true"></span>
                            <span class="sr-only">Incluir <?= e($t['nome']) ?></span>
                        </label>
                    </div>
                    <div class="tempos" role="radiogroup" aria-label="Tempo para <?= e($t['nome']) ?>">
                        <?php foreach ($t['opcoes'] as $min): ?>
                            <label class="tempo">
                                <input type="radio" name="tempo[<?= e($id) ?>]" value="<?= $min ?>" <?= $r['tempo'] === $min ? 'checked' : '' ?>>
                                <span><?= $min === 45 ? '45+ min' : $min . ' min' ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </fieldset>

        <fieldset class="cartao">
            <legend class="secao"><span class="num num--amarelo">3</span>Restrições</legend>
            <div class="chips">
                <?php foreach (RESTRICOES as $id => $r): ?>
                    <label class="chip">
                        <input type="checkbox" name="restricoes[]" value="<?= e($id) ?>" <?= in_array($id, $prefs['restricoes'], true) ? 'checked' : '' ?>>
                        <span><?= e($r['nome']) ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
            <p class="nota">Nenhuma? É só deixar tudo desmarcado.</p>
        </fieldset>

        <fieldset class="cartao">
            <legend class="secao"><span class="num num--verde">4</span>Não gosto de jeito nenhum</legend>
            <label for="nao_gosto" class="sr-only">Alimentos que você não come, separados por vírgula</label>
            <input class="campo" id="nao_gosto" type="text" name="nao_gosto" value="<?= e(implode(', ', $prefs['nao_gosto'])) ?>" placeholder="Ex.: berinjela, fígado, quiabo">
            <p class="nota">Separe por vírgula. Pratos com esses ingredientes saem do cardápio.</p>
        </fieldset>

        <button type="submit" class="botao-principal">Gerar meu cardápio <?= icone('seta', 22) ?></button>
    </form>
</div>
<?php require __DIR__ . '/includes/rodape.php'; ?>

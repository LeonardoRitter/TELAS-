<?php
/**
 * Dados do app: tipos de refeição, unidades e o banco de pratos.
 *
 * Cada ingrediente é [nome, quantidade, unidade].
 * Quantidade null = "a gosto". As quantidades valem para as porções do prato.
 * Tags: 'carne' (carne, frango, peixe), 'lactose', 'gluten'.
 */

const TIPOS = [
    'cafe'   => ['nome' => 'Café da manhã',   'tag' => 'CAFÉ DA MANHÃ',   'opcoes' => [5, 10, 15]],
    'almoco' => ['nome' => 'Almoço',          'tag' => 'ALMOÇO',          'opcoes' => [15, 30, 45]],
    'lanche' => ['nome' => 'Lanche da tarde', 'tag' => 'LANCHE DA TARDE', 'opcoes' => [5, 10, 15]],
    'jantar' => ['nome' => 'Jantar',          'tag' => 'JANTAR',          'opcoes' => [15, 30, 45]],
];

const OBJETIVOS = [
    'emagrecer' => ['nome' => 'Emagrecer',     'desc' => 'Pratos mais leves'],
    'manter'    => ['nome' => 'Manter o peso', 'desc' => 'Equilíbrio no dia a dia'],
    'massa'     => ['nome' => 'Ganhar massa',  'desc' => 'Mais proteína no prato'],
    'melhor'    => ['nome' => 'Comer melhor',  'desc' => 'Menos delivery, mais casa'],
];

const RESTRICOES = [
    'vegetariano' => ['nome' => 'Vegetariano', 'tag' => 'carne'],
    'lactose'     => ['nome' => 'Sem lactose', 'tag' => 'lactose'],
    'gluten'      => ['nome' => 'Sem glúten',  'tag' => 'gluten'],
];

// [singular, plural]
const UNIDADES = [
    'g'     => ['g', 'g'],
    'ml'    => ['ml', 'ml'],
    'un'    => ['unidade', 'unidades'],
    'fatia' => ['fatia', 'fatias'],
    'cs'    => ['colher (sopa)', 'colheres (sopa)'],
    'cc'    => ['colher (chá)', 'colheres (chá)'],
    'xic'   => ['xícara', 'xícaras'],
    'dente' => ['dente', 'dentes'],
    'folha' => ['folha', 'folhas'],
    'maco'  => ['maço', 'maços'],
    'macop' => ['maço pequeno', 'maços pequenos'],
    'lata'  => ['lata', 'latas'],
    'porc'  => ['porção', 'porções'],
];

function prato(string $tipo, string $nome, int $tempo, int $porcoes, string $nivel, array $tags, array $ingredientes, array $passos): array
{
    return compact('tipo', 'nome', 'tempo', 'porcoes', 'nivel', 'tags', 'ingredientes', 'passos');
}

const SAL = ['Sal e pimenta', null, null];

$PRATOS = [
    // ---------- CAFÉ DA MANHÃ ----------
    'iogurte-granola' => prato('cafe', 'Iogurte com granola e banana', 5, 1, 'Fácil', ['lactose', 'gluten'],
        [['Iogurte natural', 170, 'g'], ['Granola', 3, 'cs'], ['Banana', 1, 'un'], ['Mel', 1, 'cc']],
        ['Corte a banana em rodelas.', 'Coloque o iogurte numa tigela e cubra com a banana e a granola.', 'Finalize com o mel.']),
    'ovos-mexidos' => prato('cafe', 'Ovos mexidos com pão integral', 10, 1, 'Fácil', ['gluten'],
        [['Ovos', 2, 'un'], ['Pão integral', 2, 'fatia'], ['Azeite', 1, 'cc'], ['Cebolinha', null, null], SAL],
        ['Bata os ovos com sal e pimenta.', 'Aqueça o azeite em fogo baixo e mexa os ovos até ficarem cremosos.', 'Toste o pão e sirva com os ovos e a cebolinha.']),
    'overnight-oats' => prato('cafe', 'Overnight oats de morango', 5, 1, 'Fácil', ['lactose', 'gluten'],
        [['Aveia em flocos', 4, 'cs'], ['Leite', 150, 'ml'], ['Iogurte natural', 2, 'cs'], ['Morangos', 5, 'un'], ['Mel', 1, 'cc']],
        ['Misture a aveia, o leite, o iogurte e o mel num pote.', 'Tampe e deixe na geladeira durante a noite.', 'De manhã, cubra com os morangos picados.']),
    'vitamina-banana' => prato('cafe', 'Vitamina de banana com aveia', 5, 1, 'Fácil', ['lactose', 'gluten'],
        [['Banana', 1, 'un'], ['Leite', 250, 'ml'], ['Aveia em flocos', 2, 'cs'], ['Canela', null, null]],
        ['Bata tudo no liquidificador por 1 minuto.', 'Sirva gelado.']),
    'tapioca-ovo' => prato('cafe', 'Tapioca com ovo', 10, 1, 'Fácil', [],
        [['Goma de tapioca', 4, 'cs'], ['Ovo', 1, 'un'], ['Tomate', 0.5, 'un'], ['Sal', null, null]],
        ['Espalhe a goma numa frigideira quente, sem óleo, até firmar.', 'Frite o ovo à parte com uma pitada de sal.', 'Recheie a tapioca com o ovo e o tomate picado e dobre.']),
    'panqueca-banana' => prato('cafe', 'Panqueca de banana', 15, 2, 'Fácil', ['gluten'],
        [['Bananas maduras', 2, 'un'], ['Ovos', 2, 'un'], ['Aveia em flocos', 4, 'cs'], ['Óleo', 1, 'cc'], ['Canela', null, null]],
        ['Amasse as bananas e misture com os ovos e a aveia.', 'Unte a frigideira e despeje porções pequenas da massa.', 'Doure 2 minutos de cada lado e polvilhe canela.']),
    'cuscuz-ovo' => prato('cafe', 'Cuscuz com ovo', 15, 2, 'Fácil', [],
        [['Flocão de milho', 1, 'xic'], ['Água', 0.5, 'xic'], ['Ovos', 2, 'un'], ['Azeite', 1, 'cc'], ['Sal', null, null]],
        ['Hidrate o flocão com a água e o sal por 5 minutos.', 'Cozinhe na cuscuzeira por cerca de 10 minutos.', 'Frite os ovos e sirva por cima do cuscuz.']),
    'mingau-aveia' => prato('cafe', 'Mingau de aveia com canela', 10, 1, 'Fácil', ['lactose', 'gluten'],
        [['Aveia em flocos', 4, 'cs'], ['Leite', 200, 'ml'], ['Mel', 1, 'cc'], ['Canela', null, null]],
        ['Leve o leite e a aveia ao fogo baixo, mexendo sempre.', 'Cozinhe por 5 minutos até engrossar.', 'Adoce com mel e polvilhe canela.']),
    'crepioca-queijo' => prato('cafe', 'Crepioca de queijo', 10, 1, 'Fácil', ['lactose'],
        [['Ovo', 1, 'un'], ['Goma de tapioca', 2, 'cs'], ['Queijo branco', 30, 'g'], ['Sal', null, null]],
        ['Misture o ovo, a tapioca e o sal.', 'Despeje na frigideira untada e espere firmar.', 'Recheie com o queijo, dobre e deixe derreter.']),
    'pao-queijo-tomate' => prato('cafe', 'Pão integral com queijo branco e tomate', 5, 1, 'Fácil', ['lactose', 'gluten'],
        [['Pão integral', 2, 'fatia'], ['Queijo branco', 2, 'fatia'], ['Tomate', 0.5, 'un'], ['Orégano', null, null]],
        ['Toste o pão.', 'Monte com o queijo e as rodelas de tomate.', 'Finalize com orégano.']),
    'iogurte-frutas' => prato('cafe', 'Iogurte com frutas picadas', 3, 1, 'Fácil', ['lactose'],
        [['Iogurte natural', 170, 'g'], ['Mamão', 1, 'fatia'], ['Morangos', 4, 'un'], ['Mel', 1, 'cc']],
        ['Pique as frutas.', 'Misture ao iogurte e regue com o mel.']),

    // ---------- ALMOÇO ----------
    'strogonoff-frango' => prato('almoco', 'Strogonoff de frango leve com arroz', 30, 3, 'Médio', ['carne', 'lactose'],
        [['Peito de frango', 500, 'g'], ['Arroz', 1.5, 'xic'], ['Cebola', 1, 'un'], ['Champignon', 100, 'g'], ['Extrato de tomate', 2, 'cs'], ['Creme de leite light', 200, 'g'], SAL],
        ['Cozinhe o arroz.', 'Corte o frango em cubos e doure com a cebola picada.', 'Junte o extrato de tomate e o champignon e cozinhe por 10 minutos.', 'Desligue o fogo e misture o creme de leite.']),
    'frango-grelhado' => prato('almoco', 'Frango grelhado com arroz integral e salada', 25, 2, 'Fácil', ['carne'],
        [['Peito de frango', 300, 'g'], ['Arroz integral', 1, 'xic'], ['Tomate', 1, 'un'], ['Pepino', 0.5, 'un'], ['Alface', 1, 'macop'], ['Limão', 1, 'un'], ['Azeite, sal e pimenta', null, null]],
        ['Cozinhe o arroz integral em 2 xícaras de água por cerca de 20 minutos.', 'Tempere o frango com limão, sal e pimenta.', 'Grelhe o frango em frigideira com um fio de azeite, 5 minutos de cada lado.', 'Corte tomate, pepino e alface e tempere a salada na hora de servir.']),
    'bowl-grao-bico' => prato('almoco', 'Bowl de grão-de-bico com frango', 10, 1, 'Fácil', ['carne'],
        [['Grão-de-bico cozido', 150, 'g'], ['Frango grelhado (sobra)', 1, 'porc'], ['Arroz integral cozido', 0.5, 'xic'], ['Tomates-cereja', 6, 'un'], ['Azeite e limão', null, null]],
        ['Esquente o frango e o arroz.', 'Monte o bowl com o grão-de-bico e os tomates.', 'Tempere com azeite e limão.']),
    'macarrao-sugo' => prato('almoco', 'Macarrão integral ao sugo com carne moída', 25, 2, 'Fácil', ['carne', 'gluten'],
        [['Macarrão integral', 200, 'g'], ['Carne moída', 250, 'g'], ['Molho de tomate', 300, 'g'], ['Cebola', 0.5, 'un'], ['Alho', 2, 'dente'], SAL],
        ['Cozinhe o macarrão em água com sal.', 'Refogue a cebola e o alho, junte a carne e doure.', 'Acrescente o molho e cozinhe por 10 minutos.', 'Misture o molho ao macarrão.']),
    'peixe-assado' => prato('almoco', 'Peixe assado com batata e brócolis', 30, 2, 'Médio', ['carne'],
        [['Filé de tilápia', 400, 'g'], ['Batatas', 2, 'un'], ['Brócolis', 1, 'macop'], ['Limão', 1, 'un'], ['Azeite', 2, 'cs'], SAL],
        ['Preaqueça o forno a 200 °C.', 'Corte as batatas em rodelas finas e tempere com azeite.', 'Coloque o peixe temperado com limão sobre as batatas, com o brócolis ao lado.', 'Asse por 20 a 25 minutos.']),
    'hamburguer-caseiro' => prato('almoco', 'Hambúrguer caseiro com salada', 25, 2, 'Médio', ['carne'],
        [['Carne moída', 300, 'g'], ['Cebola', 0.5, 'un'], ['Ovo', 1, 'un'], ['Alface', 1, 'macop'], ['Tomate', 1, 'un'], SAL],
        ['Misture a carne, a cebola ralada, o ovo e o sal.', 'Modele 2 hambúrgueres.', 'Grelhe 4 minutos de cada lado.', 'Sirva com a salada.']),
    'panqueca-carne' => prato('almoco', 'Panqueca de carne moída', 30, 3, 'Médio', ['carne', 'lactose', 'gluten'],
        [['Farinha de trigo', 1, 'xic'], ['Leite', 1, 'xic'], ['Ovos', 2, 'un'], ['Carne moída', 300, 'g'], ['Molho de tomate', 300, 'g'], SAL],
        ['Bata a farinha, o leite e os ovos.', 'Faça as panquecas em frigideira untada.', 'Refogue a carne com metade do molho.', 'Recheie, enrole e cubra com o resto do molho.']),
    'risoto-abobrinha' => prato('almoco', 'Risoto de abobrinha', 30, 2, 'Médio', ['lactose'],
        [['Arroz arbóreo', 1, 'xic'], ['Abobrinha', 1, 'un'], ['Cebola', 0.5, 'un'], ['Caldo de legumes', 800, 'ml'], ['Queijo parmesão', 40, 'g']],
        ['Refogue a cebola e o arroz.', 'Adicione o caldo aos poucos, mexendo, por 18 minutos.', 'Junte a abobrinha ralada nos últimos 5 minutos.', 'Finalize com o parmesão.']),
    'arroz-forno' => prato('almoco', 'Arroz de forno com frango', 25, 3, 'Fácil', ['carne', 'lactose'],
        [['Arroz cozido', 3, 'xic'], ['Frango desfiado', 300, 'g'], ['Molho de tomate', 200, 'g'], ['Milho', 100, 'g'], ['Queijo muçarela', 100, 'g']],
        ['Misture o arroz, o frango, o molho e o milho.', 'Coloque numa travessa e cubra com o queijo.', 'Leve ao forno a 200 °C por 15 minutos.']),
    'escondidinho' => prato('almoco', 'Escondidinho de mandioca', 30, 3, 'Médio', ['carne', 'lactose'],
        [['Mandioca cozida', 500, 'g'], ['Carne moída', 300, 'g'], ['Leite', 0.5, 'xic'], ['Cebola', 1, 'un'], ['Queijo muçarela', 80, 'g']],
        ['Amasse a mandioca com o leite até virar purê.', 'Refogue a carne com a cebola.', 'Monte: carne embaixo, purê em cima e queijo por cima.', 'Gratine no forno por 10 minutos.']),
    'moqueca' => prato('almoco', 'Moqueca de peixe rápida', 30, 3, 'Médio', ['carne'],
        [['Filé de peixe', 500, 'g'], ['Tomates', 2, 'un'], ['Pimentão', 1, 'un'], ['Cebola', 1, 'un'], ['Leite de coco', 200, 'ml'], ['Limão e sal', null, null]],
        ['Tempere o peixe com sal e limão.', 'Faça camadas de cebola, tomate, pimentão e peixe na panela.', 'Regue com o leite de coco e cozinhe tampado por 20 minutos.']),
    'quiche-espinafre' => prato('almoco', 'Quiche de espinafre', 30, 4, 'Médio', ['lactose', 'gluten'],
        [['Massa pronta de torta', 1, 'un'], ['Ovos', 3, 'un'], ['Espinafre', 1, 'maco'], ['Creme de leite', 200, 'g'], ['Queijo ralado', 50, 'g']],
        ['Forre a forma com a massa.', 'Misture os ovos, o creme de leite, o espinafre refogado e o queijo.', 'Despeje sobre a massa.', 'Asse a 180 °C por 25 minutos.']),

    // ---------- LANCHE DA TARDE ----------
    'torrada-amendoim' => prato('lanche', 'Torrada com pasta de amendoim', 5, 1, 'Fácil', ['gluten'],
        [['Pão integral', 2, 'fatia'], ['Pasta de amendoim', 1, 'cs'], ['Banana', 0.5, 'un']],
        ['Toste o pão.', 'Espalhe a pasta de amendoim e cubra com rodelas de banana.']),
    'fruta-castanhas' => prato('lanche', 'Fruta com castanhas', 2, 1, 'Fácil', [],
        [['Maçã', 1, 'un'], ['Castanha-do-pará', 3, 'un']],
        ['Lave e corte a fruta.', 'Sirva com as castanhas.']),
    'pao-queijo-frigideira' => prato('lanche', 'Pão de queijo de frigideira', 10, 1, 'Fácil', ['lactose'],
        [['Ovo', 1, 'un'], ['Goma de tapioca', 2, 'cs'], ['Queijo ralado', 2, 'cs'], ['Requeijão', 1, 'cs']],
        ['Misture tudo até formar uma massa.', 'Despeje numa frigideira pequena untada e tampe.', 'Doure 3 minutos de cada lado.']),
    'iogurte-chia' => prato('lanche', 'Iogurte com mel e chia', 2, 1, 'Fácil', ['lactose'],
        [['Iogurte natural', 170, 'g'], ['Chia', 1, 'cs'], ['Mel', 1, 'cc']],
        ['Misture tudo e sirva.']),
    'cenoura-homus' => prato('lanche', 'Palitos de cenoura com homus', 5, 1, 'Fácil', [],
        [['Cenoura', 1, 'un'], ['Pepino', 0.5, 'un'], ['Homus', 3, 'cs']],
        ['Corte a cenoura e o pepino em palitos.', 'Sirva com o homus.']),
    'smoothie' => prato('lanche', 'Smoothie de frutas vermelhas', 5, 1, 'Fácil', ['lactose'],
        [['Frutas vermelhas congeladas', 1, 'xic'], ['Iogurte natural', 170, 'g'], ['Banana', 0.5, 'un'], ['Mel', 1, 'cc']],
        ['Bata tudo no liquidificador.', 'Sirva na hora.']),
    'bolo-caneca' => prato('lanche', 'Bolo de caneca de banana', 5, 1, 'Fácil', ['gluten'],
        [['Banana', 1, 'un'], ['Ovo', 1, 'un'], ['Aveia em flocos', 2, 'cs'], ['Fermento', 0.5, 'cc'], ['Canela', null, null]],
        ['Amasse a banana na caneca e misture o resto.', 'Leve ao micro-ondas por 2 minutos.']),
    'mix-castanhas' => prato('lanche', 'Mix de castanhas e uva-passa', 1, 1, 'Fácil', [],
        [['Castanhas variadas', 30, 'g'], ['Uva-passa', 1, 'cs']],
        ['Misture e leve num potinho.']),
    'sanduiche-natural' => prato('lanche', 'Sanduíche natural de frango', 10, 1, 'Fácil', ['carne', 'gluten', 'lactose'],
        [['Pão integral', 2, 'fatia'], ['Frango desfiado', 60, 'g'], ['Requeijão light', 1, 'cs'], ['Cenoura ralada', 2, 'cs'], ['Alface', 2, 'folha']],
        ['Misture o frango com o requeijão e a cenoura.', 'Monte o sanduíche com a alface.']),
    'banana-canela' => prato('lanche', 'Banana com canela no micro-ondas', 3, 1, 'Fácil', [],
        [['Banana', 1, 'un'], ['Mel', 1, 'cc'], ['Canela', null, null]],
        ['Corte a banana ao meio no comprimento.', 'Leve ao micro-ondas por 1 minuto.', 'Finalize com canela e mel.']),
    'cookies-aveia' => prato('lanche', 'Cookies de aveia', 15, 2, 'Fácil', ['gluten'],
        [['Aveia em flocos', 1, 'xic'], ['Banana madura', 1, 'un'], ['Uva-passa', 2, 'cs'], ['Canela', null, null]],
        ['Amasse a banana e misture com a aveia e as passas.', 'Modele os cookies numa assadeira.', 'Asse a 180 °C por 12 minutos.']),

    // ---------- JANTAR ----------
    'wrap-atum' => prato('jantar', 'Wrap de atum com salada', 10, 1, 'Fácil', ['carne', 'gluten'],
        [['Tortilha integral', 1, 'un'], ['Atum em lata', 1, 'lata'], ['Alface', 2, 'folha'], ['Tomate', 0.5, 'un'], ['Limão', 0.5, 'un']],
        ['Tempere o atum com o limão.', 'Recheie a tortilha com o atum, a alface e o tomate.', 'Enrole e toste rapidamente na frigideira.']),
    'omelete-legumes' => prato('jantar', 'Omelete de legumes com pão integral', 15, 1, 'Fácil', ['gluten'],
        [['Ovos', 2, 'un'], ['Tomate', 0.5, 'un'], ['Pimentão', 0.25, 'un'], ['Pão integral', 1, 'fatia'], SAL],
        ['Bata os ovos com sal.', 'Refogue os legumes picados por 2 minutos.', 'Junte os ovos, tampe e cozinhe por 4 minutos.', 'Sirva com o pão.']),
    'sopa-lentilha' => prato('jantar', 'Sopa de lentilha com cenoura', 30, 3, 'Fácil', [],
        [['Lentilha', 1, 'xic'], ['Cenouras', 2, 'un'], ['Cebola', 1, 'un'], ['Alho', 2, 'dente'], ['Água', 1000, 'ml'], SAL],
        ['Refogue a cebola e o alho.', 'Junte a lentilha, a cenoura em cubos e a água.', 'Cozinhe por 25 minutos, até a lentilha amaciar.']),
    'tapioca-queijo' => prato('jantar', 'Tapioca de queijo e tomate', 10, 1, 'Fácil', ['lactose'],
        [['Goma de tapioca', 4, 'cs'], ['Queijo muçarela', 40, 'g'], ['Tomate', 0.5, 'un'], ['Orégano', null, null]],
        ['Espalhe a goma na frigideira quente até firmar.', 'Recheie com o queijo e o tomate.', 'Dobre e deixe o queijo derreter.']),
    'pizza-pao-sirio' => prato('jantar', 'Pizza caseira de pão sírio', 15, 2, 'Fácil', ['lactose', 'gluten'],
        [['Pão sírio', 2, 'un'], ['Molho de tomate', 4, 'cs'], ['Queijo muçarela', 100, 'g'], ['Tomate', 1, 'un'], ['Orégano', null, null]],
        ['Preaqueça o forno a 200 °C.', 'Espalhe o molho no pão e cubra com o queijo e o tomate.', 'Asse por 8 a 10 minutos.']),
    'salada-quinoa' => prato('jantar', 'Salada morna de quinoa e legumes', 20, 1, 'Fácil', [],
        [['Quinoa', 0.5, 'xic'], ['Abobrinha', 0.5, 'un'], ['Cenoura', 0.5, 'un'], ['Grão-de-bico cozido', 80, 'g'], ['Limão', 0.5, 'un'], ['Azeite', null, null]],
        ['Cozinhe a quinoa em água por 15 minutos.', 'Salteie a abobrinha e a cenoura em cubos.', 'Misture tudo e tempere com limão e azeite.']),
    'caldo-verde' => prato('jantar', 'Caldo verde', 25, 2, 'Fácil', ['carne'],
        [['Batatas', 3, 'un'], ['Couve', 1, 'maco'], ['Linguiça calabresa', 100, 'g'], ['Cebola', 0.5, 'un'], ['Água', 1000, 'ml']],
        ['Cozinhe as batatas com a cebola na água até desmancharem.', 'Bata no liquidificador.', 'Volte ao fogo com a couve fatiada fina e a calabresa dourada por 5 minutos.']),
    'cuscuz-nordestino' => prato('jantar', 'Cuscuz nordestino com ovo e queijo coalho', 15, 1, 'Fácil', ['lactose'],
        [['Flocão de milho', 0.5, 'xic'], ['Água', 0.25, 'xic'], ['Ovo', 1, 'un'], ['Queijo coalho', 50, 'g'], ['Sal', null, null]],
        ['Hidrate o flocão com a água e o sal por 5 minutos.', 'Cozinhe na cuscuzeira por 10 minutos.', 'Sirva com o ovo frito e o queijo coalho grelhado.']),
    'sopa-abobora' => prato('jantar', 'Sopa creme de abóbora', 25, 3, 'Fácil', [],
        [['Abóbora cabotiá', 600, 'g'], ['Cebola', 1, 'un'], ['Alho', 2, 'dente'], ['Água', 800, 'ml'], ['Gengibre', null, null]],
        ['Refogue a cebola e o alho.', 'Junte a abóbora em cubos e a água e cozinhe por 20 minutos.', 'Bata no liquidificador e ajuste o sal.']),
    'crepioca-frango' => prato('jantar', 'Crepioca de frango', 15, 1, 'Fácil', ['carne'],
        [['Ovo', 1, 'un'], ['Goma de tapioca', 2, 'cs'], ['Frango desfiado', 60, 'g'], ['Tomate', 0.25, 'un'], ['Sal', null, null]],
        ['Misture o ovo, a tapioca e o sal.', 'Despeje na frigideira untada e espere firmar.', 'Recheie com o frango e o tomate e dobre.']),
    'salada-macarrao' => prato('jantar', 'Salada de macarrão com atum', 15, 2, 'Fácil', ['carne', 'gluten'],
        [['Macarrão parafuso', 150, 'g'], ['Atum em lata', 1, 'lata'], ['Tomates-cereja', 10, 'un'], ['Milho', 3, 'cs'], ['Azeite e limão', null, null]],
        ['Cozinhe o macarrão e esfrie em água corrente.', 'Misture com o atum, os tomates e o milho.', 'Tempere com azeite e limão.']),
];

// Cardápio padrão: um prato de cada tipo por dia (segunda = 0 ... domingo = 6).
const CARDAPIO_BASE = [
    ['cafe' => 'iogurte-granola',  'almoco' => 'strogonoff-frango',  'lanche' => 'torrada-amendoim',      'jantar' => 'wrap-atum'],
    ['cafe' => 'ovos-mexidos',     'almoco' => 'frango-grelhado',    'lanche' => 'fruta-castanhas',       'jantar' => 'omelete-legumes'],
    ['cafe' => 'overnight-oats',   'almoco' => 'bowl-grao-bico',     'lanche' => 'pao-queijo-frigideira', 'jantar' => 'sopa-lentilha'],
    ['cafe' => 'vitamina-banana',  'almoco' => 'macarrao-sugo',      'lanche' => 'iogurte-chia',          'jantar' => 'tapioca-queijo'],
    ['cafe' => 'tapioca-ovo',      'almoco' => 'peixe-assado',       'lanche' => 'cenoura-homus',         'jantar' => 'pizza-pao-sirio'],
    ['cafe' => 'panqueca-banana',  'almoco' => 'hamburguer-caseiro', 'lanche' => 'smoothie',              'jantar' => 'salada-quinoa'],
    ['cafe' => 'cuscuz-ovo',       'almoco' => 'panqueca-carne',     'lanche' => 'bolo-caneca',           'jantar' => 'caldo-verde'],
];

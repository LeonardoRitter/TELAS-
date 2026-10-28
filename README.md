# Dieta da Semana

App que monta o cardápio da semana (café da manhã, almoço, lanche da tarde e jantar) para quem não tem tempo de planejar.
Protótipo feito em **PHP + HTML + CSS**, sem banco de dados e sem JavaScript.

## Como rodar

### Pelo Laragon (recomendado)
1. Copie a pasta `dieta-da-semana` para `C:\laragon\www`.
2. No Laragon, clique em **Iniciar Tudo**.
3. Abra no navegador: `http://localhost/dieta-da-semana`

### Pelo terminal do VS Code (sem Laragon)
Com o PHP instalado, abra a pasta no VS Code, abra o terminal (Ctrl + ') e rode:

```
php -S localhost:8000
```

Depois abra `http://localhost:8000`.

## Telas

| Arquivo | Tela |
|---|---|
| `preferencias.php` | Objetivo, refeições do dia (com tempo de cada uma), restrições e "não gosto" |
| `semana.php` | Cardápio do dia selecionado, progresso da semana, "Outra ideia" e marcar como feito |
| `receita.php` | Ingredientes (com ajuste de porções), modo de preparo e comemoração ao concluir |

## Estrutura

```
index.php          redireciona para preferências ou para a semana
preferencias.php   tela 1
semana.php         tela 2
receita.php        tela 3
acoes.php          recebe os formulários (POST), salva na sessão e redireciona
includes/
  dados.php        tipos de refeição e banco de 45 pratos com receitas
  funcoes.php      sessão, geração do cardápio, formatação de quantidades, ícones
  topo.php         início do HTML
  rodape.php       fim do HTML
assets/
  css/style.css    todo o visual
  fonts/           Bricolage Grotesque e DM Sans (licença SIL OFL)
```

## Como funciona

- **Estado:** fica em `$_SESSION` (preferências, pratos trocados e refeições feitas). Ao virar a semana, o progresso zera.
- **Cardápio:** cada dia tem um prato base por refeição. Se ele não passa nos filtros (restrições, "não gosto" ou tempo da refeição), entra a próxima opção compatível. "Outra ideia" avança para a opção seguinte.
- **Checklists da receita:** só CSS (`:checked` e `:has()`), sem JavaScript.
- **Para recomeçar do zero:** limpe os cookies do site ou feche o navegador.

As quantidades são da receita e não substituem a orientação de um nutricionista.

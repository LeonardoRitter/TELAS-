<?php /** Início de toda página. Defina $titulo antes de incluir. */ ?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($titulo ?? 'Dieta da Semana') ?> · Dieta da Semana</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<main class="app">

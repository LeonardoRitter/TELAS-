<?php
require_once __DIR__ . '/includes/funcoes.php';

// Primeira visita: preferências. Depois: direto para a semana.
ir_para($_SESSION['gerado'] ? 'semana.php' : 'preferencias.php');

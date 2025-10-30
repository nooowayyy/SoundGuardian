<?php
// Função simples para escapar HTML
function e($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>

<?php
require_once __DIR__ . '/../app/db.php';
// fetch recent items
$res = $conn->query("SELECT id, nome, artista, tipo, foto, created_at FROM colecionaveis ORDER BY created_at DESC LIMIT 8");
$recent = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

include 'header.php';

?>


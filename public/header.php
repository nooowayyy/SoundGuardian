<?php
require_once __DIR__ . '/../app/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<header>
<h1>SoundGuardian</h1>


  <form class="search" action="search.php" method="get">
    <select name="tipo"><option value="">Todos</option><option>Vinil</option><option>CD</option><option>BoxSet</option><option>Livreto</option></select>
    <input type="text" id="search" style="width:50vh; border-radius:0px;" name="q" placeholder="Buscar por nome ou artista...">
    <button>Pesquisar</button>
  </form>
    <a href="wishlist.php" style="margin-left:10px;">Lista de Desejos</a>

</header>

<body>
    
</body>
</html>
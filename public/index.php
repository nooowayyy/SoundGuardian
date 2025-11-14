<?php
require_once __DIR__ . '/../app/db.php';
// fetch recent items
$res = $conn->query("SELECT id, nome, artista, tipo, foto, created_at FROM colecionaveis ORDER BY created_at DESC LIMIT 8");
$recent = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

include 'header.php';

?>



<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Home - Coleção</title>
<link rel="stylesheet" href="css/style.css"></head>




  
  <body>
    
    
    
    
    
    <div class="above">
      
      <section>
        
        <div class="items">
          <a href="search.php?tipo=Vinil" class="card"><img src="img/vinil.svg" alt=""></a>
          <a href="search.php?tipo=CD" class="card"><img src="img/cd.png" alt=""></a>
          <a href="search.php?tipo=BoxSet" class="card"><img src="img/box.svg" alt=""></a>
          <a href="search.php?tipo=Livreto" class="card"><img src="img/book.png" alt=""></a>
        </div>
      </section>
      
      <h2>Recentes</h2>
      <section class="recentes1">
      <button class="add">
        
      </button>
  <div class="recentes2">
    <?php foreach($recent as $it): ?>
      <div class="card" style="width:220px;padding:8px;">
        <?php if(!empty($it['foto'])): ?><img src="<?=htmlspecialchars($it['foto'])?>" style="width:100%;height:140px;object-fit:cover"><?php endif; ?>
          <h3><?=htmlspecialchars($it['nome'])?></h3>
          <p><?=htmlspecialchars($it['artista'])?> • <?=htmlspecialchars($it['tipo'])?></p>
          <a href="view_item.php?id=<?= $it['id'] ?>">Ver</a>
      </div>
      <?php endforeach; ?>
    </div>
  </section>
  
  <section>
    <h2>Favoritos</h2>
    <div style="display:flex;flex-wrap:wrap;gap:12px;">
      <?php foreach($recent as $it): ?>
        <div class="card" style="width:220px;padding:8px;">
          <?php if(!empty($it['foto'])): ?><img src="<?=htmlspecialchars($it['foto'])?>" style="width:100%;height:140px;object-fit:cover"><?php endif; ?>
            <h3><?=htmlspecialchars($it['nome'])?></h3>
            <p><?=htmlspecialchars($it['artista'])?> • <?=htmlspecialchars($it['tipo'])?></p>
            <a href="view_item.php?id=<?= $it['id'] ?>">Ver</a>
          </div>
          <?php endforeach; ?>
        </div>
      </section>
      
      <p><a href="add_item.php">Adicionar novo item</a> | <a href="list.php">Listas</a></p>
      
      
    </div>

  </div>
  
</body>


</html>
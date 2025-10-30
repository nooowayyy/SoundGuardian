<?php
require_once __DIR__ . '/../app/db.php';
// remove
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['remove'])) {
    $id = intval($_POST['id'] ?? 0);
    if ($id>0) { $s=$conn->prepare('DELETE FROM wishlist WHERE id=?'); $s->bind_param('i',$id); $s->execute(); header('Location: wishlist.php'); exit; }
}
// fetch wishlist with item info
$res = $conn->query('SELECT w.id as wid, w.nota, w.created_at, c.* FROM wishlist w JOIN colecionaveis c ON c.id=w.item_id ORDER BY w.created_at DESC');
$list = $res->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html><html><head><meta charset="utf-8"><title>Wishlist</title><link rel="stylesheet" href="css/style.css"></head><body>
<main style="max-width:900px;margin:20px auto;">
  <a href="index.php">← Voltar</a>
  <h1>Lista de Desejos</h1>
  <?php foreach($list as $it): ?>
    <div class="card" style="margin-bottom:8px;padding:8px;">
      <h3><?=htmlspecialchars($it['nome'])?> — <?=htmlspecialchars($it['artista'])?></h3>
      <p>Nota: <?=htmlspecialchars($it['nota'])?></p>
      <form method="post" style="display:inline;"><input type="hidden" name="id" value="<?=$it['wid']?>"><button name="remove">Remover</button></form>
      <a href="view_item.php?id=<?=$it['id']?>">Ver</a>
    </div>
  <?php endforeach; ?>
</main></body></html>
<?php
require_once __DIR__ . '/../app/db.php';
$id = intval($_GET['id'] ?? 0);
if ($id<=0) die('Lista inválida');
// add item to list
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_item'])) {
    $item_id = intval($_POST['item_id'] ?? 0);
    if ($item_id>0) {
        $s = $conn->prepare('INSERT INTO lista_items (lista_id, item_id) VALUES (?, ?)');
        $s->bind_param('ii', $id, $item_id); $s->execute();
        header('Location: view_list.php?id=' . $id); exit;
    }
}
// fetch list
$stmt = $conn->prepare('SELECT * FROM listas WHERE id=?'); $stmt->bind_param('i',$id); $stmt->execute(); $res=$stmt->get_result(); $list=$res->fetch_assoc();
if (!$list) die('Lista não encontrada');
// fetch items in list
$s = $conn->prepare('SELECT c.* FROM colecionaveis c JOIN lista_items li ON c.id=li.item_id WHERE li.lista_id=?');
$s->bind_param('i',$id); $s->execute(); $items = $s->get_result()->fetch_all(MYSQLI_ASSOC);
// fetch all items for adding
$all = $conn->query('SELECT id,nome,artista FROM colecionaveis ORDER BY nome')->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html><html><head><meta charset="utf-8"><title><?=htmlspecialchars($list['nome'])?></title><link rel="stylesheet" href="css/style.css"></head><body>
<main style="max-width:900px;margin:20px auto;">
  <a href="list.php">← Voltar</a>
  <h1><?=htmlspecialchars($list['nome'])?></h1>
  <p><?=htmlspecialchars($list['descricao'])?></p>

  <h3>Adicionar item</h3>
  <form method="post">
    <select name="item_id">
      <?php foreach($all as $it): ?><option value="<?=$it['id']?>"><?=htmlspecialchars($it['nome'] . ' — ' . $it['artista'])?></option><?php endforeach; ?>
    </select>
    <button name="add_item">Adicionar</button>
  </form>

  <h3>Itens da lista</h3>
  <div style="display:flex;flex-wrap:wrap;gap:12px;margin-top:12px;">
    <?php foreach($items as $it): ?>
      <div class="card" style="width:220px;padding:8px;">
        <?php if(!empty($it['foto'])): ?><img src="<?=htmlspecialchars($it['foto'])?>" style="width:100%;height:140px;object-fit:cover"><?php endif; ?>
        <h4><?=htmlspecialchars($it['nome'])?></h4>
        <p><?=htmlspecialchars($it['artista'])?></p>
        <a href="view_item.php?id=<?=$it['id']?>">Ver</a>
      </div>
    <?php endforeach; ?>
  </div>
</main></body></html>
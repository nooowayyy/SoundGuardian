<?php
require_once __DIR__ . '/../app/db.php';
// create list
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_list'])) {
    $nome = trim($_POST['nome'] ?? '');
    $desc = trim($_POST['descricao'] ?? null);
    if ($nome !== '') {
        $s = $conn->prepare('INSERT INTO listas (nome, descricao) VALUES (?, ?)');
        $s->bind_param('ss', $nome, $desc); $s->execute();
        header('Location: list.php'); exit;
    }
}

// fetch lists
$res = $conn->query('SELECT * FROM listas ORDER BY created_at DESC');
$lists = $res->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html><html><head><meta charset="utf-8"><title>Listas</title><link rel="stylesheet" href="css/style.css"></head><body>
<main style="max-width:900px;margin:20px auto;">
  <h1>Listas</h1>
  <form method="post"><label>Nome: <input name="nome" required></label>
  <label>Descrição: <input name="descricao"></label>
  <button name="create_list" type="submit">Criar lista</button></form>

  <section style="margin-top:16px;">
    <?php foreach($lists as $l): ?>
      <div class="card" style="margin-bottom:8px;padding:8px;">
        <h3><?=htmlspecialchars($l['nome'])?></h3>
        <p><?=htmlspecialchars($l['descricao'])?></p>
        <a href="view_list.php?id=<?=$l['id']?>">Abrir</a>
      </div>
    <?php endforeach; ?>
  </section>
</main></body></html>
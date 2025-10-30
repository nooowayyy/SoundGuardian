<?php
require_once __DIR__ . '/../app/db.php';
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { die('Item inválido'); }
$stmt = $conn->prepare('SELECT * FROM colecionaveis WHERE id = ?');
$stmt->bind_param('i', $id); $stmt->execute(); $res = $stmt->get_result(); $item = $res->fetch_assoc();
if (!$item) { die('Item não encontrado'); }

// handle add to wishlist POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_wishlist'])) {
    $note = $_POST['nota'] ?? null;
    $s = $conn->prepare('INSERT INTO wishlist (item_id, nota) VALUES (?, ?)');
    $s->bind_param('is', $id, $note);
    $s->execute();
    header('Location: view_item.php?id=' . $id);
    exit;
}
?>
<!doctype html><html><head><meta charset="utf-8"><title><?=htmlspecialchars($item['nome'])?></title><link rel="stylesheet" href="css/style.css"></head><body>
<main style="max-width:800px;margin:20px auto;">
  <a href="index.php">← Voltar</a>
  <h1><?=htmlspecialchars($item['nome'])?></h1>
  <p><strong>Artista:</strong> <?=htmlspecialchars($item['artista'])?></p>
  <p><strong>Tipo:</strong> <?=htmlspecialchars($item['tipo'])?></p>
  <p><strong>Gênero:</strong> <?=htmlspecialchars($item['genero'])?></p>
  <p><strong>Gravadora:</strong> <?=htmlspecialchars($item['gravadora'])?></p>
  <p><strong>Edição:</strong> <?=htmlspecialchars($item['edicao'])?> — <strong>Ano:</strong> <?=htmlspecialchars($item['ano'])?></p>
  <?php if(!empty($item['foto'])): ?><img src="<?=htmlspecialchars($item['foto'])?>" style="max-width:300px;display:block;margin:8px 0;"><?php endif; ?>
  <h3>Adicionais</h3>
  <ul>
    <?php if($item['autografado']): ?><li>Autografado</li><?php endif; ?>
    <?php if($item['importado']): ?><li>Importado (<?=htmlspecialchars($item['pais'])?>)</li><?php endif; ?>
    <?php if($item['primeira']): ?><li>Primeira edição</li><?php endif; ?>
    <?php if($item['lacrado']): ?><li>Lacrado</li><?php endif; ?>
    <?php if($item['encarte']): ?><li>Encarte incluso</li><?php endif; ?>
    <?php if($item['poster']): ?><li>Pôster incluso</li><?php endif; ?>
    <?php if($item['obi']): ?><li>OBI Strip</li><?php endif; ?>
    <?php if($item['hyper']): ?><li>Hyper Sticker</li><?php endif; ?>
  </ul>
  <h3>Anotações</h3>
  <p><?=nl2br(htmlspecialchars($item['anotacoes']))?></p>

  <p><a href="edit_item.php?id=<?=$item['id']?>">Editar</a></p>

  <section style="margin-top:16px;border-top:1px solid #eee;padding-top:12px;">
    <h3>Adicionar à Lista de Desejos</h3>
    <form method="post">
      <label>Nota: <input type="text" name="nota"></label>
      <button name="add_wishlist" type="submit">Adicionar</button>
    </form>
  </section>
</main></body></html>
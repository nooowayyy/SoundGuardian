<?php
require_once __DIR__ . '/../app/db.php';
$q = trim($_GET['q'] ?? '');
$tipo = $_GET['tipo'] ?? '';
$artista = $_GET['artista'] ?? '';
$gravadora = $_GET['gravadora'] ?? '';
$ano = $_GET['ano'] ?? '';

// build query
$where = [];
$params = [];
$types = '';
if ($q !== '') { $where[] = '(nome LIKE ? OR artista LIKE ?)'; $params[] = "%$q%"; $params[] = "%$q%"; $types .= 'ss'; }
if ($tipo !== '') { $where[] = 'tipo = ?'; $params[] = $tipo; $types .= 's'; }
if ($artista !== '') { $where[] = 'artista LIKE ?'; $params[] = "%$artista%"; $types .= 's'; }
if ($gravadora !== '') { $where[] = 'gravadora LIKE ?'; $params[] = "%$gravadora%"; $types .= 's'; }
if ($ano !== '') { $where[] = 'ano = ?'; $params[] = $ano; $types .= 's'; }

$sql = 'SELECT id,nome,artista,tipo,foto,ano FROM colecionaveis';
if ($where) { $sql .= ' WHERE ' . implode(' AND ', $where); }
$sql .= ' ORDER BY created_at DESC LIMIT 200';

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$res = $stmt->get_result();
$items = $res->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html><html><head><meta charset="utf-8"><title>Pesquisa</title><link rel="stylesheet" href="css/style.css"></head><body>
<main style="max-width:1000px;margin:20px auto;">
  <h1>Pesquisa</h1>
  <form method="get">
    <input type="text" name="q" value="<?=htmlspecialchars($q)?>" placeholder="Nome ou artista">
    <select name="tipo"><option value="">Todos</option><option <?= $tipo=='Vinil'?'selected':''?>>Vinil</option><option <?= $tipo=='CD'?'selected':''?>>CD</option><option <?= $tipo=='BoxSet'?'selected':''?>>BoxSet</option><option <?= $tipo=='Livreto'?'selected':''?>>Livreto</option></select>
    <input type="text" name="artista" value="<?=htmlspecialchars($artista)?>" placeholder="Artista">
    <input type="text" name="gravadora" value="<?=htmlspecialchars($gravadora)?>" placeholder="Gravadora">
    <input type="number" name="ano" value="<?=htmlspecialchars($ano)?>" placeholder="Ano">
    <button>Filtrar</button>
  </form>

  <div style="display:flex;flex-wrap:wrap;gap:12px;margin-top:12px;">
    <?php foreach($items as $it): ?>
      <div class="card" style="width:220px;padding:8px;">
        <?php if(!empty($it['foto'])): ?><img src="<?=htmlspecialchars($it['foto'])?>" style="width:100%;height:140px;object-fit:cover"><?php endif; ?>
        <h3><?=htmlspecialchars($it['nome'])?></h3>
        <p><?=htmlspecialchars($it['artista'])?> • <?=htmlspecialchars($it['ano'])?></p>
        <a href="view_item.php?id=<?= $it['id'] ?>">Ver</a>
      </div>
    <?php endforeach; ?>
  </div>
</main></body></html>
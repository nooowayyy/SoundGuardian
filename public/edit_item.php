<?php
require_once __DIR__ . '/../app/db.php';
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { die('ID inválido'); }

// fetch item
$stmt = $conn->prepare('SELECT * FROM colecionaveis WHERE id = ?');
$stmt->bind_param('i', $id); $stmt->execute(); $res = $stmt->get_result(); $item = $res->fetch_assoc();
if (!$item) { die('Item não encontrado'); }

$uploadDir = __DIR__ . '/uploads';
if (!is_dir($uploadDir)) mkdir($uploadDir,0755,true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'] ?? 'Vinil';
    $nome = trim($_POST['nome'] ?? '');
    $artista = trim($_POST['artista'] ?? '');
    $genero = trim($_POST['genero'] ?? null);
    $gravadora = trim($_POST['gravadora'] ?? null);
    $edicao = trim($_POST['edicao'] ?? null);
    $ano = $_POST['ano'] ?? null;
    $anotacoes = $_POST['anotacoes'] ?? null;
    $autografado = isset($_POST['autografado']) ? 1 : 0;
    $certificado = $item['certificado'];
    $importado = isset($_POST['importado']) ? 1 : 0;
    $pais = trim($_POST['pais'] ?? null);
    $primeira = isset($_POST['primeira']) ? 1 : 0;
    $lacrado = isset($_POST['lacrado']) ? 1 : 0;
    $encarte = isset($_POST['encarte']) ? 1 : 0;
    $poster = isset($_POST['poster']) ? 1 : 0;
    $obi = isset($_POST['obi']) ? 1 : 0;
    $hyper = isset($_POST['hyper']) ? 1 : 0;
    // photo upload
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $tmp = $_FILES['foto']['tmp_name'];
        $name = basename($_FILES['foto']['name']);
        $target = $uploadDir . '/' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/','_',$name);
        if (move_uploaded_file($tmp, $target)) {
            $foto_path = 'uploads/' . basename($target);
        } else { $foto_path = $item['foto']; }
    } else { $foto_path = $item['foto']; }

    // certificado upload
    if ($autografado && isset($_FILES['certificado']) && $_FILES['certificado']['error'] === UPLOAD_ERR_OK) {
        $tmp = $_FILES['certificado']['tmp_name'];
        $name = basename($_FILES['certificado']['name']);
        $target = $uploadDir . '/' . time() . '_cert_' . preg_replace('/[^a-zA-Z0-9._-]/','_',$name);
        if (move_uploaded_file($tmp,$target)) { $certificado = 'uploads/' . basename($target); }
    }

    // update
    $sql = "UPDATE colecionaveis SET tipo=?, nome=?, artista=?, genero=?, gravadora=?, edicao=?, ano=?, foto=?, autografado=?, certificado=?, importado=?, pais=?, primeira=?, lacrado=?, encarte=?, poster=?, obi=?, hyper=?, anotacoes=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssississiiiiissi', $tipo, $nome, $artista, $genero, $gravadora, $edicao, $ano, $foto_path, $autografado, $certificado, $importado, $pais, $primeira, $lacrado, $encarte, $poster, $obi, $hyper, $anotacoes, $id);
    if ($stmt->execute()) {
        header('Location: view_item.php?id=' . $id);
        exit;
    } else {
        $error = 'Erro ao atualizar: ' . $stmt->error;
    }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Editar Item</title><link rel="stylesheet" href="css/style.css"></head><body>
<main style="max-width:800px;margin:20px auto;">
  <h1>Editar Item</h1>
  <?php if(!empty($error)): ?><p style="color:#b00"><?=htmlspecialchars($error)?></p><?php endif; ?>
  <form method="post" enctype="multipart/form-data">
    <label>Tipo: <select name="tipo"><option <?= $item['tipo']=='Vinil'?'selected':''?>>Vinil</option><option <?= $item['tipo']=='CD'?'selected':''?>>CD</option><option <?= $item['tipo']=='BoxSet'?'selected':''?>>BoxSet</option><option <?= $item['tipo']=='Livreto'?'selected':''?>>Livreto</option></select></label><br><br>
    <label>Nome: <input type="text" name="nome" value="<?=htmlspecialchars($item['nome'])?>" required></label><br><br>
    <label>Artista: <input type="text" name="artista" value="<?=htmlspecialchars($item['artista'])?>" required></label><br><br>
    <label>Gênero: <input type="text" name="genero" value="<?=htmlspecialchars($item['genero'])?>"></label><br><br>
    <label>Gravadora: <input type="text" name="gravadora" value="<?=htmlspecialchars($item['gravadora'])?>"></label><br><br>
    <label>Edição: <input type="text" name="edicao" value="<?=htmlspecialchars($item['edicao'])?>"></label><br><br>
    <label>Ano: <input type="number" name="ano" min="1900" max="2100" value="<?=htmlspecialchars($item['ano'])?>"></label><br><br>
    <label>Foto: <input type="file" name="foto" accept="image/*"></label><br>
    <?php if(!empty($item['foto'])): ?><img src="<?=htmlspecialchars($item['foto'])?>" style="max-width:200px;margin-top:8px;"><?php endif; ?><br>
    <fieldset><legend>Adicionais</legend>
      <label><input type="checkbox" name="autografado" <?= $item['autografado']?'checked':''?>> Autografado</label><br>
      <label>Certificado: <input type="file" name="certificado" accept="application/pdf,image/*"></label><br>
      <label><input type="checkbox" name="importado" <?= $item['importado']?'checked':''?>> Importado</label>
      <label>País: <input type="text" name="pais" value="<?=htmlspecialchars($item['pais'])?>"></label><br>
      <label><input type="checkbox" name="primeira" <?= $item['primeira']?'checked':''?>> Primeira edição</label><br>
      <label><input type="checkbox" name="lacrado" <?= $item['lacrado']?'checked':''?>> Lacrado</label><br>
      <label><input type="checkbox" name="encarte" <?= $item['encarte']?'checked':''?>> Encarte incluso</label><br>
      <label><input type="checkbox" name="poster" <?= $item['poster']?'checked':''?>> Pôster incluso</label><br>
      <label><input type="checkbox" name="obi" <?= $item['obi']?'checked':''?>> OBI Strip</label><br>
      <label><input type="checkbox" name="hyper" <?= $item['hyper']?'checked':''?>> Hyper Sticker</label><br>
    </fieldset><br>
    <label>Anotações:<br><textarea name="anotacoes" rows="4" cols="50"><?=htmlspecialchars($item['anotacoes'])?></textarea></label><br><br>
    <button type="submit">Salvar</button>
  </form>
</main></body></html>
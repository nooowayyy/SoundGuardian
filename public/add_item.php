<?php
// public/add_item.php - processa o formulário de cadastro (minimalista, MySQLi prepared statements)
require_once __DIR__ . '/../app/db.php'; // fornece $conn

// criar pasta de uploads se não existir
$uploadDir = __DIR__ . '/uploads';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // campos principais
    $tipo = $_POST['tipo'] ?? 'Vinil';
    $nome = trim($_POST['nome'] ?? '');
    $artista = trim($_POST['artista'] ?? '');
    $genero = trim($_POST['genero'] ?? null);
    $gravadora = trim($_POST['gravadora'] ?? null);
    $edicao = trim($_POST['edicao'] ?? null);
    $ano = $_POST['ano'] ?? null;
    $anotacoes = $_POST['anotacoes'] ?? null;

    // checkbox/opcionais
    $autografado = isset($_POST['autografado']) ? 1 : 0;
    $certificado = null;
    $importado = isset($_POST['importado']) ? 1 : 0;
    $pais = trim($_POST['pais'] ?? null);
    $primeira = isset($_POST['primeira']) ? 1 : 0;
    $lacrado = isset($_POST['lacrado']) ? 1 : 0;
    $encarte = isset($_POST['encarte']) ? 1 : 0;
    $poster = isset($_POST['poster']) ? 1 : 0;
    $obi = isset($_POST['obi']) ? 1 : 0;
    $hyper = isset($_POST['hyper']) ? 1 : 0;

    // upload da foto
    $foto_path = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $tmp = $_FILES['foto']['tmp_name'];
        $name = basename($_FILES['foto']['name']);
        // evitar sobrescrever - prefixa com timestamp
        $target = $uploadDir . '/' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $name);
        if (move_uploaded_file($tmp, $target)) {
            // relative path to public folder for use in pages
            $foto_path = 'uploads/' . basename($target);
        }
    }

    // certificado de autenticidade (opcional upload) - se autografado for marcado
    if ($autografado && isset($_FILES['certificado']) && $_FILES['certificado']['error'] === UPLOAD_ERR_OK) {
        $tmp = $_FILES['certificado']['tmp_name'];
        $name = basename($_FILES['certificado']['name']);
        $target = $uploadDir . '/' . time() . '_cert_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $name);
        if (move_uploaded_file($tmp, $target)) {
            $certificado = 'uploads/' . basename($target);
        }
    }

    // validações mínimas
    if ($nome === '' || $artista === '') {
        $error = 'Nome e artista são obrigatórios.';
    } else {
        // prepared statement
        $sql = "INSERT INTO colecionaveis 
        (tipo, nome, artista, genero, gravadora, edicao, ano, foto, autografado, certificado, importado, pais, primeira, lacrado, encarte, poster, obi, hyper, anotacoes)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die('Erro prepare: ' . $conn->error);
        }
        // year may be empty - cast to null
        $ano_param = ($ano === '' ? null : $ano);
        $stmt->bind_param(
            'ssssssississiiiiiss',
            $tipo, $nome, $artista, $genero, $gravadora, $edicao, $ano_param, $foto_path,
            $autografado, $certificado, $importado, $pais, $primeira, $lacrado, $encarte, $poster, $obi, $hyper, $anotacoes
        );
        if ($stmt->execute()) {
            $success = 'Item cadastrado com sucesso.';
        } else {
            $error = 'Erro ao inserir: ' . $stmt->error;
        }
        $stmt->close();
    }
}

// Simple HTML form (minimal) if needed for testing
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Adicionar Item</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <main style="max-width:800px;margin:20px auto;">
        <h1>Adicionar Item</h1>
        <?php if (!empty($error)): ?><p style="color:#b00;"><?=htmlspecialchars($error)?></p><?php endif; ?>
        <?php if (!empty($success)): ?><p style="color:#080;"><?=htmlspecialchars($success)?></p><?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <label>Tipo:
                <select name="tipo">
                    <option>Vinil</option>
                    <option>CD</option>
                    <option>BoxSet</option>
                    <option>Livreto</option>
                </select>
            </label><br><br>
            <label>Nome: <input type="text" name="nome" required></label><br><br>
            <label>Artista: <input type="text" name="artista" required></label><br><br>
            <label>Gênero: <input type="text" name="genero"></label><br><br>
            <label>Gravadora: <input type="text" name="gravadora"></label><br><br>
            <label>Edição: <input type="text" name="edicao"></label><br><br>
            <label>Ano: <input type="number" name="ano" min="1900" max="2100"></label><br><br>
            <label>Foto (capa): <input type="file" name="foto" accept="image/*"></label><br><br>
            <fieldset>
                <legend>Adicionais</legend>
                <label><input type="checkbox" name="autografado"> Autografado</label><br>
                <label>Certificado: <input type="file" name="certificado" accept="application/pdf,image/*"></label><br>
                <label><input type="checkbox" name="importado"> Importado</label>
                <label>País: <input type="text" name="pais"></label><br>
                <label><input type="checkbox" name="primeira"> Primeira edição</label><br>
                <label><input type="checkbox" name="lacrado"> Lacrado</label><br>
                <label><input type="checkbox" name="encarte"> Encarte incluso</label><br>
                <label><input type="checkbox" name="poster"> Pôster incluso</label><br>
                <label><input type="checkbox" name="obi"> OBI Strip</label><br>
                <label><input type="checkbox" name="hyper"> Hyper Sticker</label><br>
            </fieldset><br>
            <label>Anotações:<br><textarea name="anotacoes" rows="4" cols="50"></textarea></label><br><br>
            <button type="submit">Cadastrar</button>
        </form>
    </main>
</body>
</html>

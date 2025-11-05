<?php
$coon = new mysqli("localhost", "root", "", "escola");

if ($coon->connect_error) {
    die("Erro: " . $coon->connect_error);
}

// aceitar tanto 'id' quanto 'id_funcionario' vindo do formulário
$id = isset($_POST['id']) ? $_POST['id'] : (isset($_POST['id_funcionario']) ? $_POST['id_funcionario'] : null);
$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$telefone = $_POST['telefone'] ?? '';
$cargo = $_POST['cargo'] ?? '';

if ($id === null) {
    die('ID do funcionário não informado.');
}

// Detectar coluna de ID correta (id ou id_funcionario)
$idColumn = null;
$colRes = $coon->query("SHOW COLUMNS FROM funcionarios");
if ($colRes) {
    while ($col = $colRes->fetch_assoc()) {
        if ($col['Field'] === 'id') { $idColumn = 'id'; break; }
        if ($col['Field'] === 'id_funcionario') { $idColumn = 'id_funcionario'; break; }
    }
}
if (!$idColumn) {
    die('Coluna de ID não encontrada na tabela funcionarios.');
}

$sql = "UPDATE funcionarios SET nome=?, email=?, telefone=?, cargo=? WHERE `" . $idColumn . "`=?";
$stmt = $coon->prepare($sql);
if (!$stmt) {
    die('Falha ao preparar UPDATE: ' . $coon->error);
}
$stmt->bind_param("ssssi", $nome, $email, $telefone, $cargo, $id);

?>
<!DOCTYPE html>
<html lang="pt-BR">
        <link rel="stylesheet" href="../style.css">

<head>
    <meta charset="UTF-8">
    <title>Atualização de Funcionario</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f8; display:flex; justify-content:center; padding:40px; }
        .message-box { background:#fff; padding:30px; border-radius:8px; box-shadow:0 0 10px rgba(0,0,0,0.1); text-align:center; max-width:500px; }
        h2 { color:#28a745; margin-bottom:20px; }
        .error { color:#dc3545; }
        a { display:inline-block; margin-top:20px; text-decoration:none; background:#0077cc; color:#fff; padding:10px 20px; border-radius:6px; font-weight:bold; }
        a:hover{ background:#005fa3; }
    </style>
</head>
<body>
    <div class="message-box">
        <?php
        if ($stmt->execute()) {
            echo "<h2>Dados do funcionário atualizados com sucesso!</h2>";
        } else {
            echo "<h2 class='error'>Erro ao atualizar: " . htmlspecialchars($stmt->error) . "</h2>";
        }
        ?>
        <a href="listarFuncionarios.php">Voltar para Lista de Funcionarios</a>
        <br><br>
        <a href="../index.html">Menu Principal</a>
    </div>
</body>
</html>

<?php
$stmt->close();
$coon->close();
?>

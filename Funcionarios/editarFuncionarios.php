<?php
$coon = new mysqli("localhost", "root", "", "escola");

if ($coon->connect_error) {
    die("Erro de conexão: " . $coon->connect_error);
}

if (isset($_GET['id']) || isset($_GET['id_funcionario'])) {
    $id = isset($_GET['id']) ? $_GET['id'] : $_GET['id_funcionario'];

    // Detectar qual coluna de ID existe na tabela: 'id' ou 'id_funcionario'
    $idColumn = null;
    $colRes = $coon->query("SHOW COLUMNS FROM funcionarios");
    if ($colRes) {
        while ($col = $colRes->fetch_assoc()) {
            if ($col['Field'] === 'id') { $idColumn = 'id'; break; }
            if ($col['Field'] === 'id_funcionario') { $idColumn = 'id_funcionario'; break; }
        }
    }
    if (!$idColumn) {
        echo "<p style='text-align:center;color:red;'>Coluna de ID não encontrada na tabela funcionarios.</p>";
        exit;
    }

    $sql = "SELECT * FROM funcionarios WHERE `" . $idColumn . "` = ?";
    $stmt = $coon->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<p style='text-align:center;color:red;'>Funcionario não encontrado!</p>";
        exit;
    }
} else {
    echo "<p style='text-align:center;color:red;'>ID não informado!</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Funcionario</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <nav>
            <a href="listarFuncionarios.php">Listar Funcionarios</a>
            <a href="formFuncionarios.html">Adicionar Funcionarios</a>
            <a href="../index.html">Menu Principal</a>
        </nav>

        <h2>Editar Funcionario</h2>

        <form action="atualizarFuncionarios.php" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row[$idColumn]); ?>">

            <label>Nome:</label>
            <input type="text" name="nome" value="<?php echo htmlspecialchars($row['nome'] ?? ''); ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($row['email'] ?? ''); ?>" required pattern="^[^@]+@[^@]+\.[a-zA-Z]{2,}$" title="Digite um email válido (ex: usuario@dominio.com)">

            <label>Telefone:</label>
            <input type="text" name="telefone" value="<?php echo htmlspecialchars($row['telefone'] ?? ''); ?>" maxlength="11" pattern="[0-9]{10,11}" required>

            <label>Cargo:</label>
            <input type="text" name="cargo" value="<?php echo htmlspecialchars($row['cargo'] ?? ''); ?>" required>
            
            <button type="submit">Atualizar</button>
        </form>
    </div>
</body>
</html>

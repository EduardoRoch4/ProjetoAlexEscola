<?php
$coon = new mysqli("localhost", "root", "", "escola");

if ($coon->connect_error) {
    die("Erro de conexão: " . $coon->connect_error);
}

if (isset($_GET['id_aluno'])) {
    $id = $_GET['id_aluno'];

    $stmt = $coon->prepare("SELECT * FROM alunos WHERE id_aluno = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<p style='text-align:center;color:red;'>Aluno não encontrado!</p>";
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
    <title>Editar Aluno</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <nav>
            <a href="listarAlunos.php">Listar Alunos</a>
            <a href="formAlunos.html">Adicionar Alunos</a>
            <a href="index.html">Menu Principal</a>
        </nav>

        <h2>Editar Aluno</h2>

        <form action="atualizarAlunos.php" method="POST">
            <input type="hidden" name="id_aluno" value="<?php echo $row['id_aluno']; ?>">

            <label>Nome:</label>
            <input type="text" name="nome" value="<?php echo htmlspecialchars($row['nome']); ?>" required>

            <label>Data de Nascimento:</label>
            <input type="date" name="data_nascimento" value="<?php echo $row['data_nascimento']; ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>

            <label>Telefone:</label>
            <input type="text" name="telefone" value="<?php echo htmlspecialchars($row['telefone']); ?>" required>

            <button type="submit">Atualizar</button>
        </form>
    </div>
</body>
</html>

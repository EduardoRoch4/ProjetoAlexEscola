<?php
$coon = new mysqli("localhost", "root", "", "escola");

if ($coon->connect_error) {
    die("Erro de conexão: " . $coon->connect_error);
}

if (isset($_GET['id_professor'])) {
    $id = $_GET['id_professor'];

    $stmt = $coon->prepare("SELECT * FROM professores WHERE id_professor = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<p style='text-align:center;color:red;'>Professor não encontrado!</p>";
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
    <title>Editar Professor</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <nav>
            <a href="listarProfessores.php">Listar Professores</a>
            <a href="formProfessores.html">Adicionar Professores</a>
        </nav>

        <h2>Editar Professor</h2>

        <form action="atualizarProfessores.php" method="POST">
            <input type="hidden" name="id_professor" value="<?php echo $row['id_professor']; ?>">

            <label>Nome:</label>
            <input type="text" name="nome" value="<?php echo htmlspecialchars($row['nome']); ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>

            <label>Telefone:</label>
            <input type="text" name="telefone" value="<?php echo htmlspecialchars($row['telefone']); ?>" required>

            <button type="submit">Atualizar</button>
        </form>
    </div>
</body>
</html>

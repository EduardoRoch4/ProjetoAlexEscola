<?php
$coon = new mysqli("localhost", "root", "", "escola");

if ($coon->connect_error) {
    die("Erro de conexão: " . $coon->connect_error);
}

if (isset($_GET['id_curso'])) {
    $id = $_GET['id_curso'];

    $stmt = $coon->prepare("SELECT * FROM cursos WHERE id_curso = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<p style='text-align:center;color:red;'>Curso não encontrado!</p>";
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
    <title>Editar Curso</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <nav>
            <a href="listarAlunos.php">Listar Cursos</a>
            <a href="formCursos.html">Adicionar Curso</a>
            <a href="index.html">Menu Principal</a>
        </nav>

        <h2>Editar Curso</h2>

        <form action="atualizarCursos.php" method="POST">
            <input type="hidden" name="id_curso" value="<?php echo $row['id_curso']; ?>">

            <label>Nome:</label>
            <input type="text" name="nome" value="<?php echo htmlspecialchars($row['nome']); ?>" required>

            <label>Descrição:</label>
            <input type="text" name="descricao" value="<?php echo $row['descricao']; ?>" required>

            <button type="submit">Atualizar</button>
        </form>
    </div>
</body>
</html>

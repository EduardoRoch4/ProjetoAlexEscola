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
    <link rel="stylesheet" href="FrontEnd/style.css">
</head>
<body>


    <style>
        /* FrontEnd/style.css */

body {
    font-family: Arial, sans-serif;
    background-color: #f4f6f8;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 600px;
    margin: 40px auto;
    background-color: #ffffff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

nav {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
}

nav a {
    text-decoration: none;
    color: #0077cc;
    font-weight: bold;
    padding: 8px 12px;
    border-radius: 4px;
    transition: background-color 0.3s;
}

nav a:hover {
    background-color: #e0f0ff;
}

h2 {
    text-align: center;
    color: #333333;
    margin-bottom: 25px;
}

form label {
    display: block;
    margin-bottom: 5px;
    color: #555555;
    font-weight: bold;
}

form input[type="text"],
form input[type="email"],
form input[type="date"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #cccccc;
    border-radius: 4px;
    box-sizing: border-box;
}

button[type="submit"] {
    width: 100%;
    padding: 12px;
    background-color: #0077cc;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
}

button[type="submit"]:hover {
    background-color: #005fa3;
}

    </style>

    <div class="container">
        <nav>
            <a href="listar.php">Listar Alunos</a>
            <a href="index.html">Adicionar Alunos</a>
        </nav>

        <h2>Editar Aluno</h2>

        <form action="atualizar.php" method="POST">
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

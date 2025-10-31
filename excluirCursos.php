<?php
$coon = new mysqli("localhost", "root", "", "escola");

if ($coon->connect_error) {
    die("Erro: " . $coon->connect_error);
}

$id = $_GET['id_curso'];

// Primeiro exclui as matérias relacionadas ao curso
$deleteMaterias = $coon->prepare("DELETE FROM materias WHERE id_curso = ?");
$deleteMaterias->bind_param("i", $id);
$deleteMaterias->execute();
$deleteMaterias->close();

// Agora exclui o curso
$stmt = $coon->prepare("DELETE FROM cursos WHERE id_curso = ?");
$stmt->bind_param("i", $id);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Exclusão de Cursos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            display: flex;
            justify-content: center;
            padding: 40px;
        }
        .message-box {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 500px;
        }
        h2 {
            margin-bottom: 20px;
        }
        .success {
            color: #28a745;
        }
        .error {
            color: #dc3545;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background-color: #0077cc;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        a:hover {
            background-color: #005fa3;
        }
    </style>
</head>
<body>
    <div class="message-box">
        <?php
        if ($stmt->execute()) {
            echo "<h2 class='success'>Curso excluído com sucesso!</h2>";
        } else {
            echo "<h2 class='error'>Erro ao excluir: " . $stmt->error . "</h2>";
        }
        ?>
        <a href="listarCursos.php">Voltar para Lista de Cursos</a>
        <br><br>
        <a href="index.html">Menu Principal</a>
    </div>
</body>
</html>

<?php
$stmt->close();
$coon->close();
?>

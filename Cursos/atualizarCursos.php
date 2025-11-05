<?php
$coon = new mysqli("localhost", "root", "", "escola");

if ($coon->connect_error) {
    die("Erro: " . $coon->connect_error);
}

$id = $_POST['id_curso'];
$nome = $_POST['nome'];
$descricao = $_POST['descricao'];

// Corrigido: substituído 'email' por 'descricao'
$stmt = $coon->prepare("UPDATE cursos SET nome=?, descricao=? WHERE id_curso=?");
$stmt->bind_param("ssi", $nome, $descricao, $id);
?>
<!DOCTYPE html>
<html lang="pt-BR">
        <link rel="stylesheet" href="../style.css">

<head>
    <meta charset="UTF-8">
    <title>Atualização de Curso</title>
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
            color: #28a745;
            margin-bottom: 20px;
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
            echo "<h2>Dados do curso atualizados com sucesso!</h2>";
        } else {
            echo "<h2 class='error'>Erro ao atualizar: " . $stmt->error . "</h2>";
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

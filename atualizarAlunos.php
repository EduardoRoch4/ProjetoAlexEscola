<?php
$coon = new mysqli("localhost", "root", "", "escola");

if ($coon->connect_error) {
    die("Erro: " . $coon->connect_error);
}

$id = $_POST['id_aluno'];
$nome = $_POST['nome'];
$data_nasc = $_POST['data_nascimento'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];

$stmt = $coon->prepare("UPDATE alunos SET nome=?, data_nascimento=?, email=?, telefone=? WHERE id_aluno=?");
$stmt->bind_param("ssssi", $nome, $data_nasc, $email, $telefone, $id);

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Atualização de Aluno</title>
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
            echo "<h2>Dados do aluno atualizados com sucesso!</h2>";
        } else {
            echo "<h2 class='error'>Erro ao atualizar: " . $stmt->error . "</h2>";
        }
        ?>
        <a href="listarAlunos.php">Voltar para Lista de Alunos</a>
        <br><br>
        <a href="index.html">Menu Principal</a>
    </div>
</body>
</html>

<?php
$stmt->close();
$coon->close();
?>

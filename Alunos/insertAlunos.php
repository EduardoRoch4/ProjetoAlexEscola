<?php
require_once '../conectar.php';

$msg = '';
$msgClass = '';

try {
    // Validar dados recebidos
    if (!isset($_POST['nome'], $_POST['data_nascimento'], $_POST['email'], $_POST['telefone'], $_POST['id_curso'])) {
        throw new Exception("Todos os campos são obrigatórios");
    }

    $nome = $_POST['nome'];
    $data = $_POST['data_nascimento'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $id_curso = (int)$_POST['id_curso'];

    // Inicia a transação
    $conn->begin_transaction();

    // Prepara e executa a inserção do aluno
    $stmt = $conn->prepare("INSERT INTO alunos (nome, data_nascimento, email, telefone, id_curso) VALUES (?, ?, ?, ?, ?)");
    if ($stmt === false) {
        throw new Exception("Erro ao preparar a inserção do aluno: " . $conn->error);
    }

    $stmt->bind_param("ssssi", $nome, $data, $email, $telefone, $id_curso);
    
    if (!$stmt->execute()) {
        throw new Exception("Erro ao inserir aluno: " . $stmt->error);
    }

    // Commit da transação
    $conn->commit();
    
    $msg = "Aluno cadastrado com sucesso!";
    $msgClass = "success";

} catch (Exception $e) {
    // Rollback em caso de erro
    if (isset($conn)) {
        $conn->rollback();
    }
    $msg = "Erro: " . $e->getMessage();
    $msgClass = "error";
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Aluno</title>
    <link rel="stylesheet" href="../style.css">
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
        .success h2 { color: #28a745; }
        .error h2 { color: #dc3545; }
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
    <div class="message-box <?php echo $msgClass; ?>">
        <h2><?php echo htmlspecialchars($msg); ?></h2>
        <a href="listarAlunos.php">Voltar para Lista de Alunos</a>
        <br><br>
        <a href="../index.html">Menu Principal</a>
    </div>
</body>
</html>
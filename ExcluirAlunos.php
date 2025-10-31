<?php
// Exclusão de aluno com remoção das associações em alunos_materias
$msg = '';
$msgClass = '';

$coon = new mysqli("localhost", "root", "", "escola");
if ($coon->connect_error) {
    $msg = 'Erro de conexão: ' . $coon->connect_error;
    $msgClass = 'error';
} else {
    if (!isset($_GET['id_aluno'])) {
        $msg = 'ID do aluno não especificado.';
        $msgClass = 'error';
    } else {
        $id = (int) $_GET['id_aluno'];
        $coon->begin_transaction();
        try {
            // Exclui relações em alunos_materias primeiro
            $stmt = $coon->prepare('DELETE FROM alunos_materias WHERE id_aluno = ?');
            if ($stmt === false) throw new Exception('Falha ao preparar exclusão em alunos_materias: ' . $coon->error);
            $stmt->bind_param('i', $id);
            if (!$stmt->execute()) throw new Exception('Erro ao excluir em alunos_materias: ' . $stmt->error);
            $stmt->close();

            // Agora exclui o aluno
            $stmt = $coon->prepare('DELETE FROM alunos WHERE id_aluno = ?');
            if ($stmt === false) throw new Exception('Falha ao preparar exclusão em alunos: ' . $coon->error);
            $stmt->bind_param('i', $id);
            if (!$stmt->execute()) throw new Exception('Erro ao excluir aluno: ' . $stmt->error);
            $stmt->close();

            $coon->commit();
            $msg = 'Aluno excluído com sucesso!';
            $msgClass = 'success';
        } catch (Exception $e) {
            $coon->rollback();
            $msg = 'Erro ao excluir aluno (transação revertida): ' . $e->getMessage();
            $msgClass = 'error';
        }
    }
    $coon->close();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Exclusão de Aluno</title>
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
        .message-box h2 { margin-bottom: 20px; }
        .message-box.success h2 { color: #28a745; }
        .message-box.error h2 { color: #dc3545; }
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
        a:hover { background-color: #005fa3; }
    </style>
</head>
<body>
    <div class="message-box <?php echo $msgClass; ?>">
        <h2><?php echo htmlspecialchars($msg); ?></h2>
        <a href="listarAlunos.php">Voltar para Lista de Alunos</a>
        <br><br>
        <a href="index.html">Menu Principal</a>
    </div>
</body>
</html>

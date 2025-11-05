<?php
$coon = new mysqli("localhost", "root", "", "escola");

$msg = '';
$msgClass = '';

if ($coon->connect_error) {
    $msg = 'Erro de conexão: ' . $coon->connect_error;
    $msgClass = 'error';
} else {
    if (!isset($_GET['id_professor'])) {
        $msg = 'ID do professor não especificado.';
        $msgClass = 'error';
    } else {
        $id = (int) $_GET['id_professor'];
        $coon->begin_transaction();
        try {
            // Exclui o professor
            $stmt = $coon->prepare('DELETE FROM professores WHERE id_professor = ?');
            if ($stmt === false) throw new Exception('Falha ao preparar exclusão em professores: ' . $coon->error);
            $stmt->bind_param('i', $id);
            if (!$stmt->execute()) throw new Exception('Erro ao excluir professor: ' . $stmt->error);
            $stmt->close();

            $coon->commit();
            $msg = 'Professor excluído com sucesso!';
            $msgClass = 'success';
        } catch (Exception $e) {
            $coon->rollback();
            $msg = 'Erro ao excluir professor (transação revertida): ' . $e->getMessage();
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
    <title>Exclusão de Professor</title>
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
        <a href="listarProfessores.php">Voltar para Lista de Professores</a>
        <br><br>
        <a href="../index.html">Menu Principal</a>
    </div>
</body>
</html>

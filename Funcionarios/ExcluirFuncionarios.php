<?php
$coon = new mysqli("localhost", "root", "", "escola");

$msg = '';
$msgClass = '';

if ($coon->connect_error) {
    $msg = 'Erro de conexão: ' . $coon->connect_error;
    $msgClass = 'error';
} else {
    // detectar coluna de id na tabela funcionarios
    $idCol = null;
    $colsRes = $coon->query("SHOW COLUMNS FROM funcionarios");
    if ($colsRes) {
        while ($col = $colsRes->fetch_assoc()) {
            if ($col['Field'] === 'id') { $idCol = 'id'; break; }
            if ($col['Field'] === 'id_funcionario') { $idCol = 'id_funcionario'; }
        }
    }
    if ($idCol === null) {
        // fallback: tentar primeira coluna
        $colsRes = $coon->query("SHOW COLUMNS FROM funcionarios");
        if ($colsRes && $first = $colsRes->fetch_assoc()) {
            $idCol = $first['Field'];
        }
    }

    if (!isset($_GET['id']) && !isset($_GET['id_funcionario'])) {
        $msg = 'ID do funcionario não especificado.';
        $msgClass = 'error';
    } else {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : (int) $_GET['id_funcionario'];
        $coon->begin_transaction();
        try {
            // Se tabela cursos tem coluna que referencia funcionário, apagar essas linhas
            $cursosIdCol = null;
            $colsCursos = $coon->query("SHOW COLUMNS FROM cursos");
            if ($colsCursos) {
                while ($c = $colsCursos->fetch_assoc()) {
                    if ($c['Field'] === 'id_funcionario') { $cursosIdCol = 'id_funcionario'; break; }
                    if ($c['Field'] === $idCol) { $cursosIdCol = $idCol; break; }
                }
            }
            if ($cursosIdCol) {
                $sql = "DELETE FROM cursos WHERE `" . $cursosIdCol . "` = ?";
                $stmt = $coon->prepare($sql);
                if ($stmt === false) throw new Exception('Falha ao preparar exclusão em cursos: ' . $coon->error);
                $stmt->bind_param('i', $id);
                if (!$stmt->execute()) throw new Exception('Erro ao excluir cursos: ' . $stmt->error);
                $stmt->close();
            }

            // Agora exclui o funcionário
            $sql2 = "DELETE FROM funcionarios WHERE `" . $idCol . "` = ?";
            $stmt = $coon->prepare($sql2);
            if ($stmt === false) throw new Exception('Falha ao preparar exclusão em funcionarios: ' . $coon->error);
            $stmt->bind_param('i', $id);
            if (!$stmt->execute()) throw new Exception('Erro ao excluir funcionario: ' . $stmt->error);
            $stmt->close();

            $coon->commit();
            $msg = 'Funcionario excluído com sucesso!';
            $msgClass = 'success';
        } catch (Exception $e) {
            $coon->rollback();
            $msg = 'Erro ao excluir funcionario (transação revertida): ' . $e->getMessage();
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
    <title>Exclusão de Funcionario</title>
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
        <a href="listarFuncionarios.php">Voltar para Lista de Funcionarios</a>
        <br><br>
        <a href="../index.html">Menu Principal</a>
    </div>
</body>
</html>

<?php
$conn = new mysqli("localhost", "root", "", "escola");

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$result = $conn->query("SELECT professores.*, cursos.nome AS nome_curso FROM professores LEFT JOIN cursos ON professores.id_curso = cursos.id_curso");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<link rel="stylesheet" href="../style.css">

<head>
    <meta charset="UTF-8">
    <title>Lista de Professores</title>
    <style>
        .action-buttons {
            display: flex;
            flex-direction: column;
            /* Um em cima do outro */
            gap: 6px;
            /* Espaço entre eles */
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        .container {
            width: 90%;
            max-width: 1000px;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .btn {
            text-decoration: none;
            color: #fff;
            background-color: #28a745;
            padding: 6px 10px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }

        .menu-btn {
            display: inline-block;
            margin-top: 30px;
            text-decoration: none;
            background-color: #0077cc;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .menu-btn:hover {
            background-color: #005fa3;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Lista de Professores</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Curso</th>
                <th>Ações</th>
            </tr>

            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id_professor']; ?></td>
                    <td><?php echo $row['nome']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['telefone']; ?></td>
                    <td><?php echo htmlspecialchars($row['nome_curso'] ?? '—'); ?></td>
                    <td>
                        <div class="action-buttons">
                            <a class="btn" href="editarProfessores.php?id_professor=<?= $row['id_professor']; ?>">Editar</a>
                            <a class="btnExcluir" href="ExcluirProfessores.php?id_professor=<?= $row['id_professor']; ?>">Excluir</a>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </table>

        <a class="menu-btn" href="../index.html">Voltar ao Menu Principal</a>
        <a class="menu-btn" href="formProfessores.php">Cadastrar Professor</a>
    </div>
</body>

</html>
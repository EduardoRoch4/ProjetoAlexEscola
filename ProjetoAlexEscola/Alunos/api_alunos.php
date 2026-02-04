<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../conectar.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        if (isset($_GET['id_aluno'])) {
            // Buscar um aluno específico
            $id = intval($_GET['id_aluno']);
            $stmt = $conn->prepare("SELECT a.*, c.nome AS curso_nome FROM alunos a LEFT JOIN cursos c ON a.id_curso = c.id_curso WHERE a.id_aluno = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                echo json_encode($row, JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Aluno não encontrado'], JSON_UNESCAPED_UNICODE);
            }
            $stmt->close();
        } else {
            // Listar todos os alunos
            $result = $conn->query("SELECT a.*, c.nome AS curso_nome FROM alunos a LEFT JOIN cursos c ON a.id_curso = c.id_curso ORDER BY a.nome");
            $alunos = [];
            while ($row = $result->fetch_assoc()) {
                $alunos[] = $row;
            }
            echo json_encode($alunos, JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'POST':
        $nome = $input['nome'] ?? '';
        $data_nascimento = $input['data_nascimento'] ?? '';
        $email = $input['email'] ?? '';
        $telefone = $input['telefone'] ?? '';
        $id_curso = intval($input['id_curso'] ?? 0);

        $stmt = $conn->prepare("INSERT INTO alunos (nome, data_nascimento, email, telefone, id_curso) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $nome, $data_nascimento, $email, $telefone, $id_curso);
        
        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(['success' => true, 'id_aluno' => $conn->insert_id], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Erro ao inserir aluno: ' . $stmt->error], JSON_UNESCAPED_UNICODE);
        }
        $stmt->close();
        break;

    case 'PUT':
        $id = intval($input['id_aluno'] ?? 0);
        $nome = $input['nome'] ?? '';
        $data_nascimento = $input['data_nascimento'] ?? '';
        $email = $input['email'] ?? '';
        $telefone = $input['telefone'] ?? '';
        $id_curso = intval($input['id_curso'] ?? 0);

        $stmt = $conn->prepare("UPDATE alunos SET nome=?, data_nascimento=?, email=?, telefone=?, id_curso=? WHERE id_aluno=?");
        $stmt->bind_param("ssssii", $nome, $data_nascimento, $email, $telefone, $id_curso, $id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Erro ao atualizar aluno: ' . $stmt->error], JSON_UNESCAPED_UNICODE);
        }
        $stmt->close();
        break;

    case 'DELETE':
        $id = intval($input['id_aluno'] ?? $_GET['id_aluno'] ?? 0);
        $stmt = $conn->prepare("DELETE FROM alunos WHERE id_aluno=?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Erro ao excluir aluno: ' . $stmt->error], JSON_UNESCAPED_UNICODE);
        }
        $stmt->close();
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método não permitido'], JSON_UNESCAPED_UNICODE);
        break;
}

$conn->close();
?>


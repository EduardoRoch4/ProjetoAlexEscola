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
        if (isset($_GET['id_curso'])) {
            // Buscar um curso específico
            $id = intval($_GET['id_curso']);
            $stmt = $conn->prepare("SELECT * FROM cursos WHERE id_curso = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                echo json_encode($row, JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Curso não encontrado'], JSON_UNESCAPED_UNICODE);
            }
            $stmt->close();
        } else {
            // Listar todos os cursos
            $result = $conn->query("SELECT * FROM cursos ORDER BY nome");
            $cursos = [];
            while ($row = $result->fetch_assoc()) {
                $cursos[] = $row;
            }
            echo json_encode($cursos, JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'POST':
        $nome = $input['nome'] ?? '';
        $descricao = $input['descricao'] ?? '';

        $stmt = $conn->prepare("INSERT INTO cursos (nome, descricao) VALUES (?, ?)");
        $stmt->bind_param("ss", $nome, $descricao);
        
        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(['success' => true, 'id_curso' => $conn->insert_id], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Erro ao inserir curso: ' . $stmt->error], JSON_UNESCAPED_UNICODE);
        }
        $stmt->close();
        break;

    case 'PUT':
        $id = intval($input['id_curso'] ?? 0);
        $nome = $input['nome'] ?? '';
        $descricao = $input['descricao'] ?? '';

        $stmt = $conn->prepare("UPDATE cursos SET nome=?, descricao=? WHERE id_curso=?");
        $stmt->bind_param("ssi", $nome, $descricao, $id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Erro ao atualizar curso: ' . $stmt->error], JSON_UNESCAPED_UNICODE);
        }
        $stmt->close();
        break;

    case 'DELETE':
        $id = intval($input['id_curso'] ?? $_GET['id_curso'] ?? 0);
        $stmt = $conn->prepare("DELETE FROM cursos WHERE id_curso=?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Erro ao excluir curso: ' . $stmt->error], JSON_UNESCAPED_UNICODE);
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


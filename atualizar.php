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

if ($stmt->execute()) {
    echo "Dados atualizados!<br><a href='listar.php'>Voltar</a>";
} else {
    echo "Erro: " . $stmt->error;
}

$stmt->close();
$coon->close();
?>

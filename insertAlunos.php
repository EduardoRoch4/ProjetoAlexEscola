<?php
$nome = $_POST['nome'];
$data_nasc = $_POST['data_nascimento'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];

$coon = new mysqli("localhost", "root", "", "escola");

if ($coon->connect_error) {
  die("Erro de conexão: " . $coon->connect_error);
}

$stmt = $coon->prepare("INSERT INTO alunos (nome, data_nascimento, email, telefone) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nome, $data_nasc, $email, $telefone);

if ($stmt->execute()) {
  echo "Aluno cadastrado com sucesso!<br>";
  echo "<a href='listarAlunos.php'>Voltar para Lista de Alunos</a><br>";
  echo "<a href='index.html'>Voltar ao Menu Principal</a>";
} else {
  echo "Erro: " . $stmt->error;
}

$stmt->close();
$coon->close();
?>

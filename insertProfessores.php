<?php
$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];

$coon = new mysqli("localhost", "root", "", "escola");

if ($coon->connect_error) {
  die("Erro de conexão: " . $coon->connect_error);
}

$stmt = $coon->prepare("INSERT INTO professores (nome, email, telefone) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nome, $email, $telefone);

if ($stmt->execute()) {
  echo "Professor cadastrado com sucesso!<br>";
  echo "<a href='listarProfessores.php'>Voltar para Lista de Professores</a><br>";
  echo "<a href='index.html'>Voltar ao Menu Principal</a>";
} else {
  echo "Erro: " . $stmt->error;
}

$stmt->close();
$coon->close();
?>

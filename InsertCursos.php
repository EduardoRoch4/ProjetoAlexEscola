<?php
$nome = $_POST['nome'];
$descricao = $_POST['descricao'];

$coon = new mysqli("localhost", "root", "", "escola");

if ($coon->connect_error) {
  die("Erro de conexão: " . $coon->connect_error);
} 

$stmt = $coon->prepare("INSERT INTO cursos (nome, descricao) VALUES (?, ?)");
$stmt->bind_param("ss", $nome, $descricao);

if ($stmt->execute()) {
  echo "Curso cadastrado com sucesso!<br>";
  echo "<a href='listarCursos.php'>Voltar para Lista de Cursos</a><br>";
  echo "<a href='index.html'>Voltar ao Menu Principal</a>";
} else {
  echo "Erro: " . $stmt->error;
}

$stmt->close();
$coon->close();
?>

<?php
$coon = new mysqli("localhost", "root", "", "escola");
if ($coon->connect_error) {
    die("Erro de conexão: " . $coon->connect_error);
}
$cursos = $coon->query("SELECT id_curso, nome FROM cursos");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro de Professor</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
  <div class="container">
    <nav>
      <a href="listarProfessores.php">Listar Professores</a>
      <a href="../index.html">Menu Principal</a>
    </nav>

    <h2>Cadastro de Professor</h2>
    <form action="insertProfessores.php" method="POST">
      <label>Nome:</label>
      <input type="text" name="nome" required>

      <label>Email:</label>
      <input type="email" name="email" required pattern="^[^@]+@[^@]+\.[a-zA-Z]{2,}$" title="Digite um email válido (ex: usuario@dominio.com)">

      <label>Telefone:</label>
      <input type="text" name="telefone" maxlength="11" pattern="[0-9]{11}" required>

      <label>Curso:</label>
      <select name="id_curso" required>
        <option value="">Selecione o curso</option>
        <?php while($curso = $cursos->fetch_assoc()) { ?>
          <option value="<?php echo $curso['id_curso']; ?>"><?php echo htmlspecialchars($curso['nome']); ?></option>
        <?php } ?>
      </select>
      <button type="submit">Cadastrar</button>
      </form>
  </div>
</body>
</html>

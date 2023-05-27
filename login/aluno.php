<?php
require '../config/config.php';
if (!empty($_SESSION["id_usuario"])) {
  $id = $_SESSION["id_usuario"];
  $result = mysqli_query($conn, "SELECT * FROM usuarios WHERE id_usuario = $id");
  $row = mysqli_fetch_assoc($result);
} else {
  header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="utf-8">
  <title>Tela do Aluno</title>
</head>

<body>
<h1>Tela do Aluno</h1>
  <h2>Bem-vindo, <?php echo $row["nome"]; ?></h2>
  <a href="logout.php">Logout</a>
  <a href="../remover-evento/remover_evento.php">Remover Evento</a>
  <a href="../criar-evento/criar_evento.php">Criar Evento</a>
  <a href="../tela-de-eventos/tela_de_eventos.php">Tela de eventos</a>
</body>

</html>
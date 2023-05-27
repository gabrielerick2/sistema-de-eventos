<?php
require '../config/config.php';
if (!empty($_SESSION["id_usuario"])) {
  $id = $_SESSION["id_usuario"];
  $result = mysqli_query($conn, "SELECT * FROM usuarios WHERE id_usuario = $id");
  $row = mysqli_fetch_assoc($result);

  // Verifica se o tipo de usuário é "coordenador"
  if ($row["tipo_usuario"] !== "coordenador") {
    header("Location: ../login/login.php");
    exit;
  }
} else {
  header("Location: ../login/login.php");
  exit;
}

// Query para obter todos os eventos
$sql = "SELECT id_evento, nome_evento, descricao_evento, data_hora_inicio, data_hora_fim FROM eventos";
$result = $conn->query($sql);

// Array para armazenar os eventos
$eventos = array();

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $eventos[] = $row;
  }
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Obtém o ID do evento selecionado para exclusão
  $idEvento = $_POST['id-evento'];

  // Verifica a conexão com o banco de dados novamente
  if ($conn->connect_error) {
    die('Erro na conexão com o banco de dados: ' . $conn->connect_error);
  }

  // Exclui as inscrições relacionadas ao evento
  $sqlExcluirInscricoes = "DELETE FROM inscricoes WHERE id_evento = '$idEvento'";
  if ($conn->query($sqlExcluirInscricoes) === TRUE) {
    // Exclui o evento
    $sqlExcluirEvento = "DELETE FROM eventos WHERE id_evento = '$idEvento'";
    if ($conn->query($sqlExcluirEvento) === TRUE) {
      echo '<script>alert("Evento removido com sucesso."); window.history.back();</script>';
      exit;
    } else {
      echo '<script>alert("Erro ao remover o evento: ' . $conn->error . '"); window.history.back();</script>';
      exit;
    }
  } else {
    echo '<script>alert("Erro ao remover as inscrições relacionadas ao evento: ' . $conn->error . '"); window.history.back();</script>';
    exit;
  }
}
?>

<!DOCTYPE html>
<html>

<head>  
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- font -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;700&display=swap" rel="stylesheet" />
  <!-- font-awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- style -->
  <link rel="stylesheet" type="text/css" href="./remover-evento.css">

  <title>Remover Evento</title>
</head>

<body>

  <nav class="navbar">
    <div class="nav-brand">
      <a href="#"><img src="https://www.einsteinlimeira.com.br/portal/public/assets/img/brand/logo.png" alt="Logo Einstein"></a>
    </div>
    <div class="hamburger-menu">
      <i class="fa-solid fa-bars"></i>
    </div>
    <ul class="nav-links">
      <li><a href="../tela-de-eventos/tela_de_eventos.php">Eventos</a></li>
      <li><a href="../criar-evento/criar_evento.php">Criar Evento</a></li>
      <!-- 
      <li><a href="#">2</a></li>
      <li><a href="#">3</a></li>
      <li><a href="#">4</a></li>
      -->
    </ul>
  </nav>
  <script src="../nav-bar/nav-bar.js"></script>

  <div class="remover-evento">
    <h2>Remover Evento</h2>
    <?php foreach ($eventos as $evento) { ?>
      <div class="evento">
        <h3>
          <?php echo $evento['nome_evento']; ?>
        </h3>
        <p>
          <?php echo $evento['descricao_evento']; ?>
        </p>
        <p>Data de Início:
          <?php echo $evento['data_hora_inicio']; ?>
        </p>
        <p>Data de Fim:
          <?php echo $evento['data_hora_fim']; ?>
        </p>
        <form method="POST" action="">
          <input type="hidden" name="id-evento" value="<?php echo $evento['id_evento']; ?>">
          <button type="submit">Remover Evento</button>
        </form>
      </div>
    <?php } ?>
  </div>
</body>

</html>
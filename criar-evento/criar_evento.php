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

// Verifica se os dados do formulário foram enviados
if (isset($_POST['nome-evento'])) {
    // Recebe os dados do formulário com a variável $_POST
    $nome_evento = $_POST['nome-evento'];
    $descricao_evento = $_POST['descricao-evento'];
    $data_hora_inicio = $_POST['data-hora-inicio'];
    $data_hora_fim = $_POST['data-hora-fim'];

    // Verifica se a data de início é posterior à data atual
    if (strtotime($data_hora_inicio) < time()) {
        echo '<script>alert("Data inválida."); window.location.href = "criar_evento.php";</script>';
        exit;
    }

    // Cria a consulta SQL para inserir o evento
    $sql = "INSERT INTO eventos (nome_evento, descricao_evento, data_hora_inicio, data_hora_fim)
            VALUES ('$nome_evento', '$descricao_evento', '$data_hora_inicio', '$data_hora_fim')";

    // Executa a consulta e verifica se ocorreu um erro
    if ($conn->query($sql) === TRUE) {
        echo '<script>alert("Evento criado com sucesso."); window.location.href = "criar_evento.php";</script>';
        exit;
    } else {
        echo '<script>alert("Erro ao criar o evento: ' . $conn->error . '"); window.location.href = "criar_evento.php";</script>';
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="PT-BR">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Criar Evento</title>
    <!-- font -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;700&display=swap" rel="stylesheet" />
    <!-- font-awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- style -->
    <link rel="stylesheet" href="./criarevento.css" />
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
            <li><a href="../remover-evento/remover_evento.php">Remover Evento</a></li>
            <!--
            <li><a href="#">2</a></li>
            <li><a href="#">3</a></li>
            <li><a href="#">4</a></li>
            -->
        </ul>
    </nav>
    <script src="../nav-bar/nav-bar.js"></script>


    <div class="criar-evento">
        <h2>Criar Evento</h2>

        <form method="POST" action="criar_evento.php">
            <label for="nome-evento">Nome do Evento:</label>
            <input type="text" id="nome-evento" name="nome-evento" required>

            <label for="descricao-evento">Descrição do Evento:</label>
            <textarea id="descricao-evento" name="descricao-evento" required></textarea>

            <label for="data-hora-inicio">Data e Hora de Início:</label>
            <input type="datetime-local" id="data-hora-inicio" name="data-hora-inicio" required>

            <label for="data-hora-fim">Data e Hora de Fim:</label>
            <input type="datetime-local" id="data-hora-fim" name="data-hora-fim" required>

            <button type="submit">Criar Evento</button>
        </form>
    </div>
</body>

</html>
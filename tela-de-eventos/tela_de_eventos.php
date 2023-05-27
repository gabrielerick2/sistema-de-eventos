<?php
require '../config/config.php';

$idUsuario = $_SESSION["id_usuario"];
$result = mysqli_query($conn, "SELECT * FROM usuarios WHERE id_usuario = $idUsuario");
$row = mysqli_fetch_assoc($result);

// Verifica se o tipo de usuário é "coordenador" ou "aluno"
if ($row["tipo_usuario"] !== "coordenador" && $row["tipo_usuario"] !== "aluno") {
    header("Location: ../login/login.php");
    exit;
}

// Processa o formulário de inscrição
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["id-evento"])) {
        $idEvento = $_POST["id-evento"];

        // Verifica se o usuário já está inscrito no evento
        $sqlVerificarInscricao = "SELECT * FROM inscricoes WHERE id_usuario = $idUsuario AND id_evento = $idEvento";
        $resultVerificarInscricao = mysqli_query($conn, $sqlVerificarInscricao);

        if (mysqli_num_rows($resultVerificarInscricao) > 0) {
            echo '<script>alert("Você já está inscrito neste evento."); window.location.href = "tela_de_eventos.php";</script>';
        } else {
            // Insere a inscrição no banco de dados
            $sqlInserirInscricao = "INSERT INTO inscricoes (id_usuario, id_evento) VALUES ($idUsuario, $idEvento)";
            if (mysqli_query($conn, $sqlInserirInscricao)) {
                echo '<script>alert("Inscrição realizada com sucesso."); window.location.href = "tela_de_eventos.php";</script>';
            } else {
                echo '<script>alert("Erro ao realizar inscrição."); window.location.href = "tela_de_eventos.php";</script>' . mysqli_error($conn);
            }
        }
    } else {
        echo '<script>alert("ID do evento não encontrado."); window.location.href = "tela_de_eventos.php";</script>';
    }
}

// Consulta os eventos disponíveis para inscrição
$sqlEventos = "SELECT * FROM eventos";
$resultEventos = mysqli_query($conn, $sqlEventos);
$eventos = mysqli_fetch_all($resultEventos, MYSQLI_ASSOC);
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
    <link rel="stylesheet" type="text/css" href="./tela-de-eventos.css">
    <title>Inscrição em Evento</title>
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
            <li><a href="../criar-evento/criar_evento.php">Criar Evento</a></li>
            <li><a href="../criar-evento/criar_evento.php">Remover Evento</a></li>
        </ul>
    </nav>
    <script src="../nav-bar/nav-bar.js"></script>

    <div class="inscricao-evento">
        <h2>Eventos Disponíveis para Inscrição</h2>
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
                <form method="POST" action="tela_de_eventos.php">
                    <input type="hidden" name="id-evento" value="<?php echo $evento['id_evento']; ?>">
                    <button type="submit">Inscrever-se</button>
                </form>
            </div>
        <?php } ?>
    </div>
</body>

</html>
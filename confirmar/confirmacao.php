<?php
require '../config/config.php';

$idUsuario = $_SESSION["id_usuario"];
$result = mysqli_query($conn, "SELECT * FROM usuarios WHERE id_usuario = $idUsuario");
$row = mysqli_fetch_assoc($result);

// Verifica se o tipo de usuário é "coordenador"
if ($row["tipo_usuario"] !== "coordenador") {
    header("Location: ../login/login.php");
    exit;
}

// Processa a confirmação de presença
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id-evento"])) {
    $idEvento = $_POST["id-evento"];
    $presencas = $_POST["presenca"];

    // Percorre as presenças enviadas e atualiza o status de presença de cada usuário no evento
    foreach ($presencas as $idUsuario => $presenca) {
        $sqlAtualizarPresenca = "UPDATE inscricoes SET compareceu = '$presenca' WHERE id_evento = $idEvento AND id_usuario = $idUsuario";
        mysqli_query($conn, $sqlAtualizarPresenca);
    }

    echo '<script>alert("Status de presença atualizado com sucesso."); window.location.href = "confirmacao.php";</script>';
}

// Consulta os eventos disponíveis
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
    <link rel="stylesheet" type="text/css" href="./confirmacao.css">
    <title>Confirmação de Presença</title>
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
            <li><a href="../tela-de-eventos/tela_de_eventos.php">Voltar</a></li>
        </ul>
    </nav>
    <script src="../nav-bar/nav-bar.js"></script>

    <div class="eventos-disponiveis">
        <h2>Confirmar Presença</h2>
        <?php if (!empty($eventos)) { ?>
            <?php foreach ($eventos as $evento) { ?>
                <div class="evento-box">
                    <h3><?php echo $evento['nome_evento']; ?></h3>
                    <p><?php echo $evento['descricao_evento']; ?></p>
                    <p>Data e Hora de Início: <?php echo $evento['data_hora_inicio']; ?></p>
                    <p>Data e Hora de Fim: <?php echo $evento['data_hora_fim']; ?></p>
                    <?php
                    $idEvento = $evento['id_evento'];
                    $sqlUsuariosInscritos = "SELECT i.*, u.nome FROM inscricoes i JOIN usuarios u ON i.id_usuario = u.id_usuario WHERE i.id_evento = $idEvento";
                    $resultUsuariosInscritos = mysqli_query($conn, $sqlUsuariosInscritos);
                    $usuariosInscritos = mysqli_fetch_all($resultUsuariosInscritos, MYSQLI_ASSOC);
                    ?>
                    <?php if (!empty($usuariosInscritos)) { ?>
                        <form method="POST" action="confirmacao.php">
                            <input type="hidden" name="id-evento" value="<?php echo $idEvento; ?>">
                            <table>
                                <tr>
                                    <th>Nome</th>
                                    <th>Presença</th>
                                </tr>
                                <?php foreach ($usuariosInscritos as $usuario) { ?>
                                    <tr>
                                        <td><?php echo $usuario['nome']; ?></td>
                                        <td>
                                            <input type="hidden" name="presenca[<?php echo $usuario['id_usuario']; ?>]" value="0">
                                            <input type="checkbox" name="presenca[<?php echo $usuario['id_usuario']; ?>]" value="1" <?php echo $usuario['compareceu'] ? 'checked' : ''; ?>>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                            <button type="submit">Enviar Presenças</button>
                        </form>
                    <?php } else { ?>
                        <p>Nenhum usuário inscrito no evento.</p>
                    <?php } ?>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p>Nenhum evento disponível.</p>
        <?php } ?>
    </div>
</body>

</html>
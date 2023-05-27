<?php
require '../config/config.php';

// Verificar se o usuário já está logado e redirecionar para a página inicial
if (!empty($_SESSION["id_usuario"])) {
  header("Location: index.php");
}

if (isset($_POST["submit"])) {
  $usernameemail = $_POST["usernameemail"];
  $password = $_POST["password"];

  // Consultar o banco de dados para obter os dados do usuário
  $result = mysqli_query($conn, "SELECT * FROM usuarios WHERE usuario = '$usernameemail' OR email = '$usernameemail'");
  $row = mysqli_fetch_assoc($result);

  // Verificar se o usuário foi encontrado
  if (mysqli_num_rows($result) > 0) {
    // Verificar se a senha está correta
    if ($password == $row['senha']) {
      // Iniciar a sessão e definir o ID do usuário
      $_SESSION["login"] = true;
      $_SESSION["id_usuario"] = $row["id_usuario"];

      // Verificar o tipo de usuário (aluno ou coordenador) e redirecionar para a página correspondente
      $userType = $row["tipo_usuario"];
      if ($userType == "aluno") {
        header("Location: aluno.php");
      } elseif ($userType == "coordenador") {
        header("Location: coordenador.php");
      } else {
        echo "<script> alert('Tipo de usuário inválido'); </script>";
      }
      exit();
    } else {
      echo "<script> alert('Senha incorreta'); </script>";
    }
  } else {
    echo "<script> alert('Usuário não registrado'); </script>";
  }
}
?>
<!DOCTYPE html>
<html lang="pt-BR" dir="ltr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
</head>

<body>
  <h2>Login</h2>
  <form class="" action="" method="post" autocomplete="off">
    <label for="usernameemail">Usuário ou E-mail : </label>
    <input type="text" name="usernameemail" id="usernameemail" required value=""> <br>

    <label for="password">Senha : </label>
    <input type="password" name="password" id="password" required value=""> <br>

    <button type="submit" name="submit">Login</button>
  </form>
  <br>
  <a href="registration.php">Registrar-se</a>
</body>

</html>
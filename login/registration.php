<?php
require '../config/config.php';
if (!empty($_SESSION["id_usuario"])) {
  header("Location: index.php");
}
if (isset($_POST["submit"])) {
  $name = $_POST["name"];
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $confirmpassword = $_POST["confirmpassword"];

  $duplicate = mysqli_query($conn, "SELECT * FROM usuarios WHERE usuario = '$username' OR email = '$email'");
  if (mysqli_num_rows($duplicate) > 0) {
    echo "<script> alert('Usuário ou E-mail já cadastrados'); </script>";
  } else {
    if ($password == $confirmpassword) {
      $query = "INSERT INTO usuarios (nome, usuario, email, senha) VALUES ('$name', '$username', '$email', '$password')";
      mysqli_query($conn, $query);
      echo "<script> alert('Registro realizado com sucesso'); </script>";
    } else {
      echo "<script> alert('As senhas não coincidem'); </script>";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
  <h2>Cadastro</h2>
  <form class="" action="" method="post" autocomplete="off">
    <label for="name">Nome: </label>
    <input type="text" name="name" id="name" required value=""> <br>

    <label for="username">Usuário: </label>
    <input type="text" name="username" id="username" required value=""> <br>

    <label for="email">E-mail: </label>
    <input type="email" name="email" id="email" required value=""> <br>

    <label for="password">Senha: </label>
    <input type="password" name="password" id="password" required value=""> <br>

    <label for="confirmpassword">Confirmar Senha: </label>
    <input type="password" name="confirmpassword" id="confirmpassword" required value=""> <br>

    <button type="submit" name="submit">Registrar</button>
  </form>
  <br>
  <a href="login.php">Login</a>
</body>

</html>

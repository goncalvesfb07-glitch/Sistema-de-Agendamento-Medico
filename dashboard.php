<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start ();


if (!isset($_SESSION["id"])) {
    header("Location: index.php");
    exit;

}

?>

    <!--layout temporario para teste  -->

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>

    <h1>Dashboard</h1>

    <hr>

    <h3>Bem-vindo, <?php echo $_SESSION["nome"]; ?>!</h3>

    <p><strong>E-mail:</strong> <?php echo $_SESSION["email"]; ?></p>

    <p><strong>Perfil:</strong> <?php echo $_SESSION["perfil"]; ?></p>

    <br>

    <a href="logout.php">Sair</a>

</body>
<?php
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
?>
</html>
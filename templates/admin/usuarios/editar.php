<?php
/*
|--------------------------------------------------------------------------
| Página: editar.php
|--------------------------------------------------------------------------
| Objetivo:
| Exibir o formulário de edição de usuários.
|--------------------------------------------------------------------------
*/

require_once "../../../includes/verificar_admin.php";
require_once "../../../config/conexao.php";

if (!isset($_GET["id"])) {

    header("Location: index.php");
    exit;

}

$id =(int) $_GET["id"];

$sql ="SELECT
            id,
            nome,
            email,
            perfil
        FROM usuarios
        WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $id);

$stmt->execute();

$resultado = $stmt->get_result();

$usuario = $resultado->fetch_assoc();

if (!$usuario) { 

    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <title>Editar Usuário</title>

</head>

<body>

    <h1>Editar Usuário</h1>

    <form action="../../../actions/usuarios/editar.php" method="POST">

        <input
            type="hidden"
            name="id"
            value="<?= $usuario["id"]; ?>">

        <label>Nome:</label><br>

        <input
            type="text"
            name="nome"
            value="<?= htmlspecialchars($usuario["nome"]); ?>"
            required>

        <br><br>

        <label>E-mail:</label><br>

        <input
            type="email"
            name="email"
            value="<?= htmlspecialchars($usuario["email"]); ?>"
            required>

        <br><br>

        <label>Perfil:</label><br>

        <select name="perfil" required>

            <option value="Administrador"
                <?= $usuario["perfil"] == "Administrador" ? "selected" : ""; ?>>
                Administrador
            </option>

            <option value="Recepcionista"
                <?= $usuario["perfil"] == "Recepcionista" ? "selected" : ""; ?>>
                Recepcionista
            </option>

            <option value="Medico"
                <?= $usuario["perfil"] == "Medico" ? "selected" : ""; ?>>
                Médico
            </option>

        </select>

        <br><br>

        <button type="submit">

            Salvar Alterações

        </button>

    </form>

</body>

</html>

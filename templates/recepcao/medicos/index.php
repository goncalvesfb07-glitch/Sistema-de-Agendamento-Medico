<?php

session_start();

/*
|--------------------------------------------------------------------------
| Página: consultar médicos
|--------------------------------------------------------------------------
| Permite que a recepção visualize os médicos cadastrados.
|--------------------------------------------------------------------------
*/

// Verifica se o usuário está logado
if (!isset($_SESSION["id"])) {
    header("Location: ../../../public/index.php");
    exit;
}

// Verifica se o usuário é recepcionista
if ($_SESSION["perfil"] !== "Recepcionista") {
    header("Location: ../dashboard.php");
    exit;
}

require_once "../../../config/conexao.php";

/*
|--------------------------------------------------------------------------
| Busca os médicos cadastrados
|--------------------------------------------------------------------------
*/

$sql = "SELECT
            m.id,
            u.nome,
            u.email,
            m.crm_numero,
            m.crm_uf,
            m.telefone,
            m.ativo,
            GROUP_CONCAT(
                e.nome
                ORDER BY e.nome
                SEPARATOR ', '
            ) AS especialidades

        FROM medicos m

        INNER JOIN usuarios u
            ON u.id = m.usuario_id

        LEFT JOIN medicos_especialidades me
            ON me.medico_id = m.id

        LEFT JOIN especialidades e
            ON e.id = me.especialidade_id

        GROUP BY
            m.id,
            u.nome,
            u.email,
            m.crm_numero,
            m.crm_uf,
            m.telefone,
            m.ativo

        ORDER BY u.nome ASC";

$stmt = $conn->prepare($sql);
$stmt->execute();

$resultado = $stmt->get_result();

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

```
<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Médicos | Clínica Vida+</title>

<link
    rel="stylesheet"
    href="../../../public/css/app.css"
>
```

</head>

<body>

<div class="layout">

```
<!-- MENU LATERAL -->
<aside class="sidebar">

    <div class="brand">
        Clínica Vida+
        <small>Recepção</small>
    </div>

    <nav class="nav">

        <a href="../dashboard.php">
            Dashboard
        </a>

        <a href="../pacientes/index.php">
            Pacientes
        </a>

        <a class="active" href="index.php">
            Médicos
        </a>

        <a href="../agenda/index.php">
            Agenda
        </a>

        <a href="../consultas/index.php">
            Consultas
        </a>

        <a href="../../../logout.php">
            Sair
        </a>

    </nav>

</aside>


<!-- CONTEÚDO PRINCIPAL -->
<main class="main">

    <div class="topbar">

        <div>

            <h1>Médicos</h1>

            <p>
                Consulte os médicos cadastrados na clínica.
            </p>

        </div>

        <div class="user">

            <?= htmlspecialchars($_SESSION["nome"] ?? "") ?>

        </div>

    </div>


    <!-- CARD -->
    <div class="card">

        <div class="card-header">

            <div>

                <h2>Médicos cadastrados</h2>

                <p>
                    Lista de profissionais disponíveis no sistema.
                </p>

            </div>

        </div>


        <div class="table-container">

            <table>

                <thead>

                    <tr>

                        <th>Nome</th>

                        <th>CRM</th>

                        <th>Especialidade</th>

                        <th>Telefone</th>

                        <th>E-mail</th>

                        <th>Status</th>

                    </tr>

                </thead>


                <tbody>

                    <?php if ($resultado->num_rows > 0): ?>

                        <?php while ($medico = $resultado->fetch_assoc()): ?>

                            <tr>

                                <td>

                                    <strong>
                                        <?= htmlspecialchars(
                                            $medico["nome"]
                                        ); ?>
                                    </strong>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $medico["crm_numero"]
                                    ); ?>

                                    /

                                    <?= htmlspecialchars(
                                        $medico["crm_uf"]
                                    ); ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $medico["especialidades"]
                                        ?? "Não informada"
                                    ); ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $medico["telefone"]
                                        ?? "Não informado"
                                    ); ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $medico["email"]
                                    ); ?>

                                </td>


                                <td>

                                    <?php if ($medico["ativo"] == 1): ?>

                                        <span class="status status-active">
                                            Ativo
                                        </span>

                                    <?php else: ?>

                                        <span class="status status-inactive">
                                            Inativo
                                        </span>

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endwhile; ?>


                    <?php else: ?>

                        <tr>

                            <td
                                colspan="6"
                                class="empty"
                            >

                                Nenhum médico cadastrado.

                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>


        <div class="card-footer">

            <a
                class="btn btn-secondary"
                href="../dashboard.php"
            >
                Voltar para o Dashboard
            </a>

        </div>

    </div>

</main>
```

</div>

<script src="../../../public/js/app.js"></script>

</body>

</html>

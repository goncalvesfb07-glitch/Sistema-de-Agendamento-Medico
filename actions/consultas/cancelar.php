<?php

/*
|--------------------------------------------------------------------------
| US023 - Cancelar Consulta
|--------------------------------------------------------------------------
*/

require_once "../../includes/verificar_recepcao.php";
require_once "../../config/conexao.php";


if ($_SERVER["REQUEST_METHOD"] !== "GET") {

    header(
        "Location: ../../templates/recepcao/consultas/index.php"
    );

    exit;
}


$id = filter_input(
    INPUT_GET,
    "id",
    FILTER_VALIDATE_INT
);


if (!$id) {

    $_SESSION["erro"] =
        "Consulta inválida.";

    header(
        "Location: ../../templates/recepcao/consultas/index.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Verifica consulta
|--------------------------------------------------------------------------
*/

$sql = "SELECT
            id,
            status

        FROM consultas

        WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $id
);

$stmt->execute();

$resultado = $stmt->get_result();


if ($resultado->num_rows === 0) {

    $_SESSION["erro"] =
        "Consulta não encontrada.";

    header(
        "Location: ../../templates/recepcao/consultas/index.php"
    );

    exit;
}


$consulta = $resultado->fetch_assoc();


if ($consulta["status"] === "Cancelada") {

    $_SESSION["erro"] =
        "Esta consulta já está cancelada.";

    header(
        "Location: ../../templates/recepcao/consultas/index.php"
    );

    exit;
}


if ($consulta["status"] === "Finalizada") {

    $_SESSION["erro"] =
        "Uma consulta finalizada não pode ser cancelada.";

    header(
        "Location: ../../templates/recepcao/consultas/index.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Cancela
|--------------------------------------------------------------------------
*/

$sql = "UPDATE consultas

        SET
            status = 'Cancelada',
            updated_at = CURRENT_TIMESTAMP

        WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $id
);


if ($stmt->execute()) {

    $_SESSION["sucesso"] =
        "Consulta cancelada com sucesso.";

} else {

    $_SESSION["erro"] =
        "Não foi possível cancelar a consulta.";
}


header(
    "Location: ../../templates/recepcao/consultas/index.php"
);

exit;
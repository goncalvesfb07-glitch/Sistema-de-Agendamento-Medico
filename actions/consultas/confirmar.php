<?php

/*
|--------------------------------------------------------------------------
| US024 - Confirmar Presença
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
| Busca consulta
|--------------------------------------------------------------------------
*/

$sql = "SELECT
            id,
            status,
            hora_checkin

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


/*
|--------------------------------------------------------------------------
| Valida status
|--------------------------------------------------------------------------
*/

if ($consulta["status"] === "Cancelada") {

    $_SESSION["erro"] =
        "Não é possível confirmar presença em uma consulta cancelada.";

    header(
        "Location: ../../templates/recepcao/consultas/index.php"
    );

    exit;
}


if ($consulta["status"] === "Finalizada") {

    $_SESSION["erro"] =
        "Esta consulta já foi finalizada.";

    header(
        "Location: ../../templates/recepcao/consultas/index.php"
    );

    exit;
}


if (!empty($consulta["hora_checkin"])) {

    $_SESSION["erro"] =
        "A presença desta consulta já foi confirmada.";

    header(
        "Location: ../../templates/recepcao/consultas/index.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Registra check-in
|--------------------------------------------------------------------------
*/

$sql = "UPDATE consultas

        SET
            hora_checkin = CURRENT_TIMESTAMP,
            updated_at = CURRENT_TIMESTAMP

        WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $id
);


if ($stmt->execute()) {

    $_SESSION["sucesso"] =
        "Presença confirmada com sucesso.";

} else {

    $_SESSION["erro"] =
        "Não foi possível confirmar a presença.";
}


header(
    "Location: ../../templates/recepcao/consultas/index.php"
);

exit;
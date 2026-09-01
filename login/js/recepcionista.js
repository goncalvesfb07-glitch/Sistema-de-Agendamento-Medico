// ======================================
// DATA E HORA
// ======================================

function atualizarDataHora() {

    var agora = new Date();

    var data =
        document.getElementById("currentDate");

    var hora =
        document.getElementById("currentTime");


    if (data) {

        data.textContent =
            agora.toLocaleDateString(
                "pt-BR",
                {
                    weekday: "long",
                    day: "2-digit",
                    month: "long",
                    year: "numeric"
                }
            );

    }


    if (hora) {

        hora.textContent =
            agora.toLocaleTimeString(
                "pt-BR"
            );

    }

}


atualizarDataHora();

setInterval(
    atualizarDataHora,
    1000
);
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


// ======================================
// NOME DO PACIENTE
// ======================================

var pacienteNome =
    localStorage.getItem(
        "pacienteLogado"
    );


if (pacienteNome) {

    var nomeCompleto =
        pacienteNome;

    var primeiroNome =
        nomeCompleto.split(" ")[0];


    var nameElement =
        document.getElementById(
            "patientName"
        );

    var sidebarElement =
        document.getElementById(
            "sidebarName"
        );


    if (nameElement) {

        nameElement.textContent =
            primeiroNome;

    }


    if (sidebarElement) {

        sidebarElement.textContent =
            nomeCompleto;

    }

}


// ======================================
// LOGOUT
// ======================================

var logoutButton =
    document.getElementById(
        "logoutButton"
    );


if (logoutButton) {

    logoutButton.addEventListener(
        "click",
        function () {

            localStorage.removeItem(
                "pacienteLogado"
            );

            window.location.href =
                "../login.html";

        }
    );

}
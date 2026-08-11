```javascript id="u2m6l4"
var currentDate = document.getElementById("currentDate");
var currentTime = document.getElementById("currentTime");
var welcomeDate = document.getElementById("welcomeDate");
var logoutButton = document.getElementById("logoutButton");


// ================================
// DATA E HORA
// ================================

function atualizarDataHora() {

    var agora = new Date();

    var horas = String(agora.getHours()).padStart(2, "0");
    var minutos = String(agora.getMinutes()).padStart(2, "0");
    var segundos = String(agora.getSeconds()).padStart(2, "0");

    currentTime.textContent =
        horas + ":" + minutos + ":" + segundos;


    var data = agora.toLocaleDateString(
        "pt-BR",
        {
            weekday: "long",
            day: "2-digit",
            month: "long",
            year: "numeric"
        }
    );

    currentDate.textContent = data;


    var dataCurta = agora.toLocaleDateString(
        "pt-BR",
        {
            day: "2-digit",
            month: "2-digit",
            year: "numeric"
        }
    );

    welcomeDate.textContent = dataCurta;
}


atualizarDataHora();

setInterval(atualizarDataHora, 1000);


// ================================
// SAIR
// ================================

logoutButton.onclick = function () {

    localStorage.removeItem("tipoUsuario");

    window.location.href =
        "../login.html";
};


// ================================
// BOTÃO ATENDER
// ================================

var botaoAtender =
    document.querySelector(".primary-button");


if (botaoAtender) {

    botaoAtender.onclick = function () {

        window.location.href =
            "prontuario.html";

    };

}
```

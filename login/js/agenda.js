```javascript
var selectedDate = new Date();

var selectedDateElement =
    document.getElementById("selectedDate");

var previousDay =
    document.getElementById("previousDay");

var nextDay =
    document.getElementById("nextDay");

var statusFilter =
    document.getElementById("statusFilter");

var searchPatient =
    document.getElementById("searchPatient");

var appointments =
    document.querySelectorAll(".agenda-appointment");


function atualizarData() {

    var texto =
        selectedDate.toLocaleDateString(
            "pt-BR",
            {
                weekday: "long",
                day: "2-digit",
                month: "long"
            }
        );

    texto =
        texto.charAt(0).toUpperCase() +
        texto.slice(1);

    selectedDateElement.textContent = texto;
}


previousDay.onclick = function () {

    selectedDate.setDate(
        selectedDate.getDate() - 1
    );

    atualizarData();

};


nextDay.onclick = function () {

    selectedDate.setDate(
        selectedDate.getDate() + 1
    );

    atualizarData();

};


function filtrarConsultas() {

    var status =
        statusFilter.value;

    var busca =
        searchPatient.value.toLowerCase();


    appointments.forEach(function (consulta) {

        var consultaStatus =
            consulta.getAttribute("data-status");

        var paciente =
            consulta.getAttribute("data-patient")
            .toLowerCase();


        var correspondeStatus =
            status === "todos" ||
            consultaStatus === status;


        var correspondeBusca =
            paciente.includes(busca);


        if (
            correspondeStatus &&
            correspondeBusca
        ) {

            consulta.style.display = "grid";

        } else {

            consulta.style.display = "none";

        }

    });

}


statusFilter.onchange =
    filtrarConsultas;


searchPatient.oninput =
    filtrarConsultas;


function atenderPaciente(nome) {

    localStorage.setItem(
        "pacienteSelecionado",
        nome
    );

    window.location.href =
        "prontuario.html";

}


function abrirProntuario(nome) {

    localStorage.setItem(
        "pacienteSelecionado",
        nome
    );

    window.location.href =
        "prontuario.html";

}


atualizarData();
```

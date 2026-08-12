```javascript
var patientSearch =
    document.getElementById("patientSearch");

var patientFilter =
    document.getElementById("patientFilter");

var patientRows =
    document.querySelectorAll(".patient-row");

var noResults =
    document.getElementById("noResults");

var totalPatients =
    document.getElementById("totalPatients");


// ================================
// PESQUISA E FILTRO
// ================================

function filtrarPacientes() {

    var busca =
        patientSearch.value
            .toLowerCase()
            .trim();

    var filtro =
        patientFilter.value;

    var encontrados = 0;


    patientRows.forEach(function (paciente) {

        var nome =
            paciente
                .getAttribute("data-name")
                .toLowerCase();

        var cpf =
            paciente
                .getAttribute("data-cpf")
                .toLowerCase();

        var status =
            paciente.getAttribute("data-status");


        var correspondeBusca =
            nome.includes(busca) ||
            cpf.includes(busca);


        var correspondeFiltro =
            filtro === "todos" ||
            filtro === status;


        if (
            correspondeBusca &&
            correspondeFiltro
        ) {

            paciente.style.display =
                "grid";

            encontrados++;

        } else {

            paciente.style.display =
                "none";

        }

    });


    if (encontrados === 0) {

        noResults.style.display =
            "block";

    } else {

        noResults.style.display =
            "none";

    }

}


patientSearch.oninput =
    filtrarPacientes;


patientFilter.onchange =
    filtrarPacientes;


// ================================
// ABRIR PACIENTE
// ================================

function abrirPaciente(nome) {

    localStorage.setItem(
        "pacienteSelecionado",
        nome
    );

    window.location.href =
        "prontuario.html";

}


// ================================
// CONTADOR
// ================================

totalPatients.textContent =
    patientRows.length;
```

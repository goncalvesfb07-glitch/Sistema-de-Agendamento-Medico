var doctorSearch =
    document.getElementById("doctorSearch");

var specialtyFilter =
    document.getElementById("specialtyFilter");

var doctorRows =
    document.querySelectorAll(".doctor-row");

var noDoctors =
    document.getElementById("noDoctors");

var totalDoctors =
    document.getElementById("totalDoctors");


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
// PESQUISA
// ======================================

function filtrarMedicos() {

    var busca =
        doctorSearch.value
            .toLowerCase()
            .trim();

    var especialidade =
        specialtyFilter.value;

    var encontrados = 0;


    doctorRows.forEach(function (medico) {

        var nome =
            medico
                .getAttribute("data-name")
                .toLowerCase();

        var especialidadeMedico =
            medico.getAttribute(
                "data-specialty"
            );


        var correspondeNome =
            nome.includes(busca);


        var correspondeEspecialidade =
            especialidade === "todos" ||
            especialidadeMedico === especialidade;


        if (
            correspondeNome &&
            correspondeEspecialidade
        ) {

            medico.style.display =
                "grid";

            encontrados++;

        } else {

            medico.style.display =
                "none";

        }

    });


    if (encontrados === 0) {

        noDoctors.style.display =
            "block";

    } else {

        noDoctors.style.display =
            "none";

    }

}


doctorSearch.oninput =
    filtrarMedicos;


specialtyFilter.onchange =
    filtrarMedicos;


// ======================================
// HORÁRIOS
// ======================================

function verHorarios(nome) {

    localStorage.setItem(
        "medicoSelecionado",
        nome
    );

    window.location.href =
        "agendar.html";

}


// ======================================
// CONTADOR
// ======================================

totalDoctors.textContent =
    doctorRows.length;
// ======================================
// ELEMENTOS
// ======================================

var doctor =
    document.getElementById("doctor");

var patient =
    document.getElementById("patient");

var appointmentDate =
    document.getElementById("appointmentDate");

var appointmentTime =
    document.getElementById("appointmentTime");

var hoursList =
    document.getElementById("hoursList");

var availableHours =
    document.getElementById("availableHours");


// ======================================
// DADOS DOS MÉDICOS
// ======================================

var doctors = {

    "Carlos Silva": {
        specialty: "Clínico Geral",
        days: "Segunda, Quarta e Sexta",
        room: "Sala 204 — 2º andar"
    },

    "Mariana Souza": {
        specialty: "Cardiologia",
        days: "Terça e Quinta",
        room: "Sala 301 — 3º andar"
    },

    "Rafael Santos": {
        specialty: "Dermatologia",
        days: "Segunda, Terça e Quinta",
        room: "Sala 105 — 1º andar"
    },

    "Ana Souza": {
        specialty: "Ortopedia",
        days: "Segunda e Quarta",
        room: "Sala 402 — 4º andar"
    },

    "Fernanda Oliveira": {
        specialty: "Pediatria",
        days: "Terça, Quinta e Sexta",
        room: "Sala 202 — 2º andar"
    },

    "Paulo Mendes": {
        specialty: "Clínico Geral",
        days: "Terça, Quinta e Sexta",
        room: "Sala 205 — 2º andar"
    }

};


// ======================================
// HORÁRIOS
// ======================================

var availableTimes = [

    "08:00",
    "08:30",
    "09:00",
    "09:30",
    "10:00",
    "10:30",
    "11:00",
    "11:30",
    "13:00",
    "13:30",
    "14:00",
    "14:30",
    "15:00",
    "15:30",
    "16:00",
    "16:30",
    "17:00"

];


// ======================================
// DATA MÍNIMA
// ======================================

var today =
    new Date();

var year =
    today.getFullYear();

var month =
    String(
        today.getMonth() + 1
    ).padStart(2, "0");

var day =
    String(
        today.getDate()
    ).padStart(2, "0");

var todayString =
    year + "-" + month + "-" + day;

appointmentDate.min =
    todayString;


// ======================================
// CARREGAR MÉDICO SELECIONADO
// ======================================

var savedDoctor =
    localStorage.getItem(
        "medicoSelecionado"
    );

if (savedDoctor && doctors[savedDoctor]) {

    doctor.value =
        savedDoctor;

    atualizarMedico();

    localStorage.removeItem(
        "medicoSelecionado"
    );

}


// ======================================
// ATUALIZAR MÉDICO
// ======================================

function atualizarMedico() {

    var selected =
        doctor.value;

    var specialty =
        document.getElementById(
            "doctorSpecialty"
        );

    var days =
        document.getElementById(
            "doctorDays"
        );

    var room =
        document.getElementById(
            "doctorRoom"
        );


    if (!selected) {

        specialty.textContent = "—";
        days.textContent = "—";
        room.textContent = "—";

        limparHorarios();

        atualizarResumo();

        return;
    }


    var data =
        doctors[selected];


    specialty.textContent =
        data.specialty;

    days.textContent =
        data.days;

    room.textContent =
        data.room;


    gerarHorarios();

    atualizarResumo();

}


doctor.addEventListener(
    "change",
    atualizarMedico
);


// ======================================
// GERAR HORÁRIOS
// ======================================

function gerarHorarios() {

    hoursList.innerHTML = "";

    appointmentTime.innerHTML =
        '<option value="">Selecione um horário</option>';


    if (
        !doctor.value ||
        !appointmentDate.value
    ) {

        availableHours.style.display =
            "none";

        return;

    }


    availableHours.style.display =
        "block";


    availableTimes.forEach(
        function (time, index) {

            var button =
                document.createElement(
                    "button"
                );

            button.type =
                "button";

            button.className =
                "hour-button";

            button.textContent =
                time;


            /*
             * Alguns horários ficam
             * ocupados apenas para
             * simulação.
             */

            var occupied =
                index === 2 ||
                index === 8 ||
                index === 13;


            if (occupied) {

                button.classList.add(
                    "unavailable"
                );

                button.disabled =
                    true;

            } else {

                button.addEventListener(
                    "click",
                    function () {

                        selecionarHorario(
                            time,
                            button
                        );

                    }
                );

            }


            hoursList.appendChild(
                button
            );


            if (!occupied) {

                var option =
                    document.createElement(
                        "option"
                    );

                option.value =
                    time;

                option.textContent =
                    time;

                appointmentTime.appendChild(
                    option
                );

            }

        }
    );

}


// ======================================
// SELECIONAR HORÁRIO
// ======================================

function selecionarHorario(
    time,
    button
) {

    var buttons =
        document.querySelectorAll(
            ".hour-button"
        );


    buttons.forEach(
        function (item) {

            item.classList.remove(
                "selected"
            );

        }
    );


    button.classList.add(
        "selected"
    );


    appointmentTime.value =
        time;


    atualizarResumo();

}


// ======================================
// ALTERAÇÃO DE DATA
// ======================================

appointmentDate.addEventListener(
    "change",
    function () {

        gerarHorarios();

        atualizarResumo();

    }
);


// ======================================
// ALTERAÇÃO DE HORÁRIO
// ======================================

appointmentTime.addEventListener(
    "change",
    function () {

        document
            .querySelectorAll(
                ".hour-button"
            )
            .forEach(
                function (button) {

                    button.classList.remove(
                        "selected"
                    );

                    if (
                        button.textContent ===
                        appointmentTime.value
                    ) {

                        button.classList.add(
                            "selected"
                        );

                    }

                }
            );


        atualizarResumo();

    }
);


// ======================================
// PACIENTE
// ======================================

patient.addEventListener(
    "change",
    atualizarResumo
);


// ======================================
// RESUMO
// ======================================

function atualizarResumo() {

    var summaryDoctor =
        document.getElementById(
            "summaryDoctor"
        );

    var summaryInfo =
        document.getElementById(
            "summaryInfo"
        );


    if (!doctor.value) {

        summaryDoctor.textContent =
            "Médico não selecionado";

        summaryInfo.textContent =
            "Selecione os dados da consulta.";

        return;

    }


    var doctorData =
        doctors[doctor.value];


    summaryDoctor.textContent =
        doctor.value;


    var texto =
        doctorData.specialty;


    if (patient.value) {

        texto +=
            " · " + patient.value;

    }


    if (appointmentDate.value) {

        var partes =
            appointmentDate.value.split("-");

        texto +=
            " · " +
            partes[2] +
            "/" +
            partes[1] +
            "/" +
            partes[0];

    }


    if (appointmentTime.value) {

        texto +=
            " às " +
            appointmentTime.value;

    }


    summaryInfo.textContent =
        texto;

}


// ======================================
// LIMPAR HORÁRIOS
// ======================================

function limparHorarios() {

    hoursList.innerHTML = "";

    appointmentTime.innerHTML =
        '<option value="">Selecione primeiro o médico e a data</option>';

    availableHours.style.display =
        "none";

}


// ======================================
// SUBMIT
// ======================================

document
    .getElementById("appointmentForm")
    .addEventListener(
        "submit",
        function (event) {

            event.preventDefault();


            if (
                !doctor.value ||
                !patient.value ||
                !appointmentDate.value ||
                !appointmentTime.value
            ) {

                alert(
                    "Preencha médico, paciente, data e horário."
                );

                return;

            }


            var data =
                doctors[doctor.value];


            var consulta = {

                medico:
                    doctor.value,

                especialidade:
                    data.specialty,

                paciente:
                    patient.value,

                data:
                    appointmentDate.value,

                horario:
                    appointmentTime.value,

                sala:
                    data.room,

                observacoes:
                    document.getElementById(
                        "notes"
                    ).value

            };


            localStorage.setItem(
                "ultimaConsulta",
                JSON.stringify(
                    consulta
                )
            );


            alert(
                "Consulta agendada com sucesso!"
            );


            window.location.href =
                "dashboard.html";

        }
    );


// ======================================
// DATA E HORA
// ======================================

function atualizarDataHora() {

    var agora =
        new Date();


    var data =
        document.getElementById(
            "currentDate"
        );

    var hora =
        document.getElementById(
            "currentTime"
        );


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
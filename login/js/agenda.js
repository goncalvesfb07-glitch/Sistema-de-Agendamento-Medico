// ======================================
// DATA SELECIONADA
// ======================================

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

var agendaList =
    document.getElementById("agendaList");



// ======================================
// CONSULTAS
// ======================================

var consultas =
    JSON.parse(
        localStorage.getItem("clinicaConsultas")
    ) || [];



// ======================================
// MÉDICO LOGADO
// ======================================

var medicoLogado =
    JSON.parse(
        localStorage.getItem("usuarioLogado")
    ) || null;


// Nome do médico da agenda.
// Caso o login ainda não tenha nome,
// usamos Carlos Silva como padrão.

var nomeMedico =
    medicoLogado &&
    medicoLogado.nome
        ? medicoLogado.nome
        : "Carlos Silva";


// Remove "Dr." / "Dra." para facilitar
// a comparação entre os dados.

function normalizarNome(nome) {

    return String(nome || "")
        .replace(/^Dr\.\s*/i, "")
        .replace(/^Dra\.\s*/i, "")
        .trim()
        .toLowerCase();

}



// ======================================
// DATA
// ======================================

function formatarData(data) {

    var ano =
        data.getFullYear();

    var mes =
        String(
            data.getMonth() + 1
        ).padStart(2, "0");

    var dia =
        String(
            data.getDate()
        ).padStart(2, "0");

    return (
        ano +
        "-" +
        mes +
        "-" +
        dia
    );

}


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

    selectedDateElement.textContent =
        texto;

}



// ======================================
// MUDAR DIA
// ======================================

previousDay.onclick = function () {

    selectedDate.setDate(
        selectedDate.getDate() - 1
    );

    atualizarData();

    renderizarAgenda();

};


nextDay.onclick = function () {

    selectedDate.setDate(
        selectedDate.getDate() + 1
    );

    atualizarData();

    renderizarAgenda();

};



// ======================================
// STATUS
// ======================================

function normalizarStatus(status) {

    status =
        String(
            status || "agendada"
        ).toLowerCase();

    if (
        status.includes("conclu") ||
        status.includes("realiz") ||
        status.includes("finaliz") ||
        status.includes("atendid")
    ) {

        return "concluida";

    }

    if (
        status.includes("aguard")
    ) {

        return "aguardando";

    }

    if (
        status.includes("cancel")
    ) {

        return "cancelada";

    }

    return "agendada";

}



// ======================================
// BUSCAR CONSULTAS DO MÉDICO
// ======================================

function minhasConsultas() {

    return consultas.filter(
        function (consulta) {

            var medico =
                normalizarNome(
                    consulta.medicoNome ||
                    consulta.nomeMedico ||
                    consulta.medico
                );

            return (
                medico ===
                normalizarNome(nomeMedico)
            );

        }
    );

}



// ======================================
// RENDERIZAR AGENDA
// ======================================

function renderizarAgenda() {

    if (!agendaList)
        return;


    var dataSelecionada =
        formatarData(
            selectedDate
        );


    var statusSelecionado =
        statusFilter.value;


    var busca =
        searchPatient.value
            .trim()
            .toLowerCase();


    var consultasDoMedico =
        minhasConsultas();


    var consultasDoDia =
        consultasDoMedico.filter(
            function (consulta) {

                return (
                    String(
                        consulta.data ||
                        ""
                    ) === dataSelecionada
                );

            }
        );


    var filtradas =
        consultasDoDia.filter(
            function (consulta) {

                var status =
                    normalizarStatus(
                        consulta.status
                    );


                var paciente =
                    String(
                        consulta.pacienteNome ||
                        consulta.nomePaciente ||
                        consulta.paciente ||
                        ""
                    );


                var correspondeStatus =
                    statusSelecionado ===
                        "todos" ||
                    status ===
                        statusSelecionado;


                var correspondeBusca =
                    !busca ||
                    paciente
                        .toLowerCase()
                        .includes(busca);


                return (
                    correspondeStatus &&
                    correspondeBusca
                );

            }
        );


    filtradas.sort(
        function (a, b) {

            return String(
                a.horario ||
                a.hora ||
                ""
            ).localeCompare(
                String(
                    b.horario ||
                    b.hora ||
                    ""
                )
            );

        }
    );


    agendaList.innerHTML = "";


    if (
        filtradas.length === 0
    ) {

        agendaList.innerHTML = `

            <div class="empty-agenda">

                <strong>
                    Nenhuma consulta encontrada
                </strong>

                <span>
                    Não existem consultas agendadas para este dia.
                </span>

            </div>

        `;

        atualizarEstatisticas(
            consultasDoDia
        );

        return;

    }


    filtradas.forEach(
        function (consulta) {

            criarConsulta(
                consulta
            );

        }
    );


    atualizarEstatisticas(
        consultasDoDia
    );

}



// ======================================
// CRIAR CONSULTA
// ======================================

function criarConsulta(
    consulta
) {

    var paciente =
        consulta.pacienteNome ||
        consulta.nomePaciente ||
        consulta.paciente ||
        "Paciente";


    var horario =
        consulta.horario ||
        consulta.hora ||
        "--:--";


    var especialidade =
        consulta.especialidade ||
        "Consulta";


    var sala =
        consulta.sala ||
        "Sala não informada";


    var observacoes =
        consulta.observacoes ||
        "";


    var status =
        normalizarStatus(
            consulta.status
        );


    var div =
        document.createElement(
            "article"
        );


    div.className =
        "agenda-appointment";


    div.setAttribute(
        "data-status",
        status
    );


    div.setAttribute(
        "data-patient",
        paciente
    );


    var iniciais =
        paciente
            .trim()
            .split(" ")
            .filter(Boolean)
            .slice(0, 2)
            .map(
                function (nome) {

                    return nome
                        .charAt(0)
                        .toUpperCase();

                }
            )
            .join("");


    var statusTexto =
        "Agendada";


    var statusClasse =
        "scheduled";


    if (
        status === "aguardando"
    ) {

        statusTexto =
            "Aguardando";

        statusClasse =
            "waiting";

    }


    if (
        status === "concluida"
    ) {

        statusTexto =
            "Concluída";

        statusClasse =
            "completed";

    }


    if (
        status === "cancelada"
    ) {

        statusTexto =
            "Cancelada";

        statusClasse =
            "cancelled";

    }


    var botaoTexto =
        status === "concluida"
            ? "Ver prontuário"
            : "Atender";


    div.innerHTML = `

        <div class="agenda-time">

            <strong>
                ${horario}
            </strong>

            <span>
                30 min
            </span>

        </div>


        <div class="agenda-patient">

            <div class="patient-avatar-small">
                ${iniciais}
            </div>

            <div>

                <strong>
                    ${paciente}
                </strong>

                <span>
                    ${especialidade}
                </span>

            </div>

        </div>


        <div class="agenda-location">

            <span>
                Sala
            </span>

            <strong>
                ${sala.replace("Sala ", "")}
            </strong>

            ${
                observacoes
                    ? `<small>${observacoes}</small>`
                    : `<small>Atendimento</small>`
            }

        </div>


        <span class="status ${statusClasse}">
            ${statusTexto}
        </span>


        <button
            class="appointment-button ${
                status === "aguardando" ||
                status === "agendada"
                    ? "primary"
                    : ""
            }"
        >
            ${botaoTexto}
        </button>

    `;


    var botao =
        div.querySelector(
            ".appointment-button"
        );


    botao.addEventListener(
        "click",
        function () {

            abrirProntuario(
                consulta
            );

        }
    );


    agendaList.appendChild(
        div
    );

}



// ======================================
// ESTATÍSTICAS
// ======================================

function atualizarEstatisticas(
    lista
) {

    var total = lista.length;

    var concluidas =
        lista.filter(
            function (consulta) {

                return (
                    normalizarStatus(
                        consulta.status
                    ) ===
                    "concluida"
                );

            }
        ).length;


    var aguardando =
        lista.filter(
            function (consulta) {

                return (
                    normalizarStatus(
                        consulta.status
                    ) ===
                    "aguardando"
                );

            }
        ).length;


    var agendadas =
        lista.filter(
            function (consulta) {

                return (
                    normalizarStatus(
                        consulta.status
                    ) ===
                    "agendada"
                );

            }
        ).length;


    var totalElement =
        document.getElementById(
            "totalAppointments"
        );

    var completedElement =
        document.getElementById(
            "completedAppointments"
        );

    var waitingElement =
        document.getElementById(
            "waitingAppointments"
        );

    var scheduledElement =
        document.getElementById(
            "scheduledAppointments"
        );


    if (totalElement)
        totalElement.textContent =
            total;


    if (completedElement)
        completedElement.textContent =
            concluidas;


    if (waitingElement)
        waitingElement.textContent =
            aguardando;


    if (scheduledElement)
        scheduledElement.textContent =
            agendadas;

}



// ======================================
// FILTROS
// ======================================

statusFilter.onchange =
    renderizarAgenda;


searchPatient.oninput =
    renderizarAgenda;



// ======================================
// ABRIR PRONTUÁRIO
// ======================================

function abrirProntuario(
    consulta
) {

    var paciente =
        consulta.pacienteNome ||
        consulta.nomePaciente ||
        consulta.paciente ||
        "";


    localStorage.setItem(
        "pacienteSelecionado",
        paciente
    );


    // Guarda a consulta inteira para
    // o prontuário saber qual atendimento
    // está sendo realizado.

    localStorage.setItem(
        "consultaSelecionada",
        JSON.stringify(
            consulta
        )
    );


    window.location.href =
        "prontuario.html";

}



// ======================================
// COMPATIBILIDADE
// ======================================

function atenderPaciente(
    nome
) {

    localStorage.setItem(
        "pacienteSelecionado",
        nome
    );


    window.location.href =
        "prontuario.html";

}



// ======================================
// DATA E HORA DO TOPO
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



// ======================================
// INICIAR
// ======================================

atualizarData();

renderizarAgenda();
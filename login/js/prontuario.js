// ======================================
// ELEMENTOS
// ======================================

var form =
    document.getElementById("medicalRecordForm");

var patientName =
    document.getElementById("patientName");

var patientInitials =
    document.getElementById("patientInitials");

var saveMessage =
    document.getElementById("saveMessage");

var cancelButton =
    document.getElementById("cancelButton");


// ======================================
// PACIENTE SELECIONADO
// ======================================

var paciente =
    localStorage.getItem("pacienteSelecionado");


// Usuário/paciente selecionado
var pacienteSelecionado = null;

try {

    pacienteSelecionado =
        JSON.parse(
            localStorage.getItem(
                "pacienteSelecionadoDados"
            )
        );

} catch (erro) {

    pacienteSelecionado = null;

}


// ======================================
// MOSTRAR PACIENTE
// ======================================

if (paciente) {

    patientName.textContent =
        paciente;


    var partes =
        paciente
            .trim()
            .split(" ")
            .filter(Boolean);


    var iniciais =
        partes[0]
            ? partes[0].charAt(0)
            : "";


    if (partes.length > 1) {

        iniciais +=
            partes[partes.length - 1]
                .charAt(0);

    }


    patientInitials.textContent =
        iniciais.toUpperCase();

}


// ======================================
// DATA
// ======================================

var consultationDate =
    document.getElementById(
        "consultationDate"
    );


var hoje =
    new Date();


if (consultationDate) {

    consultationDate.textContent =
        hoje.toLocaleDateString(
            "pt-BR"
        );

}


// ======================================
// PEGAR VALOR DE CAMPO
// ======================================

function valor(id) {

    var campo =
        document.getElementById(id);


    if (!campo) {

        return "";

    }


    return campo.value.trim();

}


// ======================================
// SALVAR PRONTUÁRIO
// ======================================

if (form) {

    form.onsubmit =
        function (event) {

            event.preventDefault();


            // ==============================
            // BUSCAR PRONTUÁRIOS EXISTENTES
            // ==============================

            var prontuarios =
                JSON.parse(
                    localStorage.getItem(
                        "clinicaProntuarios"
                    )
                ) || [];


            // ==============================
            // DADOS DO MÉDICO LOGADO
            // ==============================

            var medicoLogado = null;


            try {

                medicoLogado =
                    JSON.parse(
                        localStorage.getItem(
                            "usuarioLogado"
                        )
                    );

            } catch (erro) {

                medicoLogado = null;

            }


            // ==============================
            // DADOS DO PACIENTE
            // ==============================

            var pacienteId = "";

            var pacienteEmail = "";


            if (pacienteSelecionado) {

                pacienteId =
                    String(
                        pacienteSelecionado.id ||
                        pacienteSelecionado.pacienteId ||
                        ""
                    );


                pacienteEmail =
                    String(
                        pacienteSelecionado.email ||
                        pacienteSelecionado.pacienteEmail ||
                        ""
                    ).toLowerCase();

            }


            // ==============================
            // PRONTUÁRIO
            // ==============================

            var prontuario = {

                id:
                    Date.now().toString(),


                paciente:
                    patientName
                        ? patientName.textContent
                        : paciente || "Paciente",


                pacienteId:
                    pacienteId,


                pacienteEmail:
                    pacienteEmail,


                data:
                    new Date()
                        .toISOString(),


                // ==========================
                // INFORMAÇÕES DA CONSULTA
                // ==========================

                queixa:
                    valor("complaint"),


                sintomas:
                    valor("complaint"),


                anamnese:
                    valor("anamnesis"),


                historico:
                    valor("medicalHistory"),


                alergias:
                    valor("allergies"),


                medicamentos:
                    valor("medications"),


                // ==========================
                // SINAIS VITAIS
                // ==========================

                pressao:
                    valor("bloodPressure"),


                frequencia:
                    valor("heartRate"),


                temperatura:
                    valor("temperature"),


                saturacao:
                    valor("oxygen"),


                // ==========================
                // EXAME
                // ==========================

                exame:
                    valor("physicalExam"),


                // ==========================
                // DIAGNÓSTICO
                // ==========================

                diagnostico:
                    valor("diagnosis"),


                // ==========================
                // PRESCRIÇÃO
                // ==========================

                prescricao:
                    valor("prescription"),


                receita:
                    valor("prescription"),


                // ==========================
                // OBSERVAÇÕES
                // ==========================

                observacoes:
                    valor("observations"),


                // ==========================
                // MÉDICO
                // ==========================

                medico:
                    medicoLogado
                        ? (
                            medicoLogado.nome ||
                            medicoLogado.usuario ||
                            "Médico"
                        )
                        : "Médico",


                medicoNome:
                    medicoLogado
                        ? (
                            medicoLogado.nome ||
                            medicoLogado.usuario ||
                            "Médico"
                        )
                        : "Médico",


                medicoId:
                    medicoLogado
                        ? String(
                            medicoLogado.id ||
                            ""
                        )
                        : "",


                especialidade:
                    medicoLogado
                        ? (
                            medicoLogado.especialidade ||
                            "Especialidade não informada"
                        )
                        : "Especialidade não informada"

            };


            // ==================================
            // ADICIONAR À LISTA
            // ==================================

            prontuarios.push(
                prontuario
            );


            // ==================================
            // SALVAR
            // ==================================

            localStorage.setItem(
                "clinicaProntuarios",
                JSON.stringify(
                    prontuarios
                )
            );


            // ==================================
            // MANTER COMPATIBILIDADE
            // ==================================

            localStorage.setItem(
                "prontuario_" +
                prontuario.paciente,
                JSON.stringify(
                    prontuario
                )
            );


            // ==================================
            // MENSAGEM
            // ==================================

            if (saveMessage) {

                saveMessage.textContent =
                    "Prontuário salvo com sucesso.";


                saveMessage.style.display =
                    "block";


                saveMessage.style.background =
                    "#eaf8ef";


                saveMessage.style.color =
                    "#16a34a";

            }


            // ==================================
            // TOPO
            // ==================================

            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });

        };

}


// ======================================
// CANCELAR
// ======================================

if (cancelButton) {

    cancelButton.onclick =
        function () {

            var confirmar =
                confirm(
                    "Deseja cancelar? Os dados preenchidos não serão salvos."
                );


            if (confirmar) {

                window.location.href =
                    "agenda.html";

            }

        };

}
```javascript
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


// ================================
// PACIENTE SELECIONADO
// ================================

var paciente =
    localStorage.getItem("pacienteSelecionado");


if (paciente) {

    patientName.textContent = paciente;

    var partes =
        paciente.trim().split(" ");

    var iniciais =
        partes[0].charAt(0);

    if (partes.length > 1) {

        iniciais +=
            partes[partes.length - 1].charAt(0);

    }

    patientInitials.textContent =
        iniciais.toUpperCase();

}


// ================================
// DATA
// ================================

var consultationDate =
    document.getElementById("consultationDate");

if (consultationDate) {

    consultationDate.textContent =
        new Date().toLocaleDateString(
            "pt-BR"
        );

}


// ================================
// SALVAR
// ================================

form.onsubmit = function (event) {

    event.preventDefault();


    var prontuario = {

        paciente: patientName.textContent,

        data:
            new Date().toLocaleDateString(
                "pt-BR"
            ),

        queixa:
            document.getElementById(
                "complaint"
            ).value,

        anamnese:
            document.getElementById(
                "anamnesis"
            ).value,

        historico:
            document.getElementById(
                "medicalHistory"
            ).value,

        alergias:
            document.getElementById(
                "allergies"
            ).value,

        medicamentos:
            document.getElementById(
                "medications"
            ).value,

        pressao:
            document.getElementById(
                "bloodPressure"
            ).value,

        frequencia:
            document.getElementById(
                "heartRate"
            ).value,

        temperatura:
            document.getElementById(
                "temperature"
            ).value,

        saturacao:
            document.getElementById(
                "oxygen"
            ).value,

        exame:
            document.getElementById(
                "physicalExam"
            ).value,

        diagnostico:
            document.getElementById(
                "diagnosis"
            ).value,

        prescricao:
            document.getElementById(
                "prescription"
            ).value,

        observacoes:
            document.getElementById(
                "observations"
            ).value,

        medico:
            "Dr. Carlos Silva"

    };


    // SALVA O PRONTUÁRIO

    localStorage.setItem(
        "prontuario_" +
        patientName.textContent,
        JSON.stringify(prontuario)
    );


    saveMessage.textContent =
        "Prontuário salvo com sucesso.";

    saveMessage.style.display =
        "block";

    saveMessage.style.background =
        "#eaf8ef";

    saveMessage.style.color =
        "#16a34a";


    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });

};


// ================================
// CANCELAR
// ================================

cancelButton.onclick = function () {

    var confirmar =
        confirm(
            "Deseja cancelar? Os dados preenchidos não serão salvos."
        );


    if (confirmar) {

        window.location.href =
            "agenda.html";

    }

};
```

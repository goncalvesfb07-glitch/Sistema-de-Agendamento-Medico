var search =
    document.getElementById("patientSearch");

var rows =
    document.querySelectorAll(".patient-row");

var noResults =
    document.getElementById("noResults");

var modal =
    document.getElementById("patientModal");

var newButton =
    document.getElementById("newPatientButton");

var closeButton =
    document.getElementById("closeModal");

var cancelButton =
    document.getElementById("cancelButton");

var form =
    document.getElementById("patientForm");


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

search.addEventListener(
    "input",
    function () {

        var value =
            search.value
                .toLowerCase()
                .replace(/\D/g, "");

        var text =
            search.value
                .toLowerCase()
                .trim();

        var found = 0;


        rows.forEach(
            function (row) {

                var name =
                    row.getAttribute(
                        "data-name"
                    ).toLowerCase();

                var cpf =
                    row.getAttribute(
                        "data-cpf"
                    );


                if (
                    name.includes(text) ||
                    cpf.includes(value)
                ) {

                    row.style.display =
                        "grid";

                    found++;

                } else {

                    row.style.display =
                        "none";

                }

            }
        );


        if (found === 0) {

            noResults.style.display =
                "block";

        } else {

            noResults.style.display =
                "none";

        }

    }
);


// ======================================
// ABRIR MODAL
// ======================================

function abrirModal() {

    modal.classList.add("show");

    document
        .getElementById("fullName")
        .focus();

}


newButton.addEventListener(
    "click",
    abrirModal
);


// ======================================
// FECHAR MODAL
// ======================================

function fecharModal() {

    modal.classList.remove("show");

}


closeButton.addEventListener(
    "click",
    fecharModal
);


cancelButton.addEventListener(
    "click",
    fecharModal
);


modal.addEventListener(
    "click",
    function (event) {

        if (
            event.target === modal
        ) {

            fecharModal();

        }

    }
);


// ======================================
// CPF
// ======================================

document
    .getElementById("cpf")
    .addEventListener(
        "input",
        function () {

            var value =
                this.value
                    .replace(/\D/g, "")
                    .slice(0, 11);


            if (value.length > 9) {

                value =
                    value.replace(
                        /(\d{3})(\d{3})(\d{3})(\d{1,2})/,
                        "$1.$2.$3-$4"
                    );

            } else if (value.length > 6) {

                value =
                    value.replace(
                        /(\d{3})(\d{3})(\d{1,3})/,
                        "$1.$2.$3"
                    );

            } else if (value.length > 3) {

                value =
                    value.replace(
                        /(\d{3})(\d{1,3})/,
                        "$1.$2"
                    );

            }


            this.value =
                value;

        }
    );


// ======================================
// TELEFONE
// ======================================

document
    .getElementById("phone")
    .addEventListener(
        "input",
        function () {

            var value =
                this.value
                    .replace(/\D/g, "")
                    .slice(0, 11);


            if (value.length > 10) {

                value =
                    value.replace(
                        /(\d{2})(\d{5})(\d{4})/,
                        "($1) $2-$3"
                    );

            } else if (value.length > 6) {

                value =
                    value.replace(
                        /(\d{2})(\d{4})(\d{1,4})/,
                        "($1) $2-$3"
                    );

            } else if (value.length > 2) {

                value =
                    value.replace(
                        /(\d{2})(\d{1,5})/,
                        "($1) $2"
                    );

            }


            this.value =
                value;

        }
    );


// ======================================
// CADASTRAR
// ======================================

form.addEventListener(
    "submit",
    function (event) {

        event.preventDefault();


        var name =
            document
                .getElementById("fullName")
                .value
                .trim();

        var cpf =
            document
                .getElementById("cpf")
                .value
                .trim();

        var birth =
            document
                .getElementById("birthDate")
                .value;

        var phone =
            document
                .getElementById("phone")
                .value
                .trim();

        var email =
            document
                .getElementById("email")
                .value
                .trim();

        var address =
            document
                .getElementById("address")
                .value
                .trim();


        var patients =
            JSON.parse(
                localStorage.getItem(
                    "pacientesCadastrados"
                )
            ) || [];


        var patient = {

            nome: name,

            cpf: cpf,

            nascimento: birth,

            telefone: phone,

            email: email,

            endereco: address

        };


        patients.push(patient);


        localStorage.setItem(
            "pacientesCadastrados",
            JSON.stringify(
                patients
            )
        );


        alert(
            "Paciente cadastrado com sucesso!"
        );


        form.reset();

        fecharModal();

    }
);


// ======================================
// VISUALIZAR
// ======================================

function visualizarPaciente(nome) {

    localStorage.setItem(
        "pacienteSelecionado",
        nome
    );

    alert(
        "Paciente selecionado: " + nome
    );

}
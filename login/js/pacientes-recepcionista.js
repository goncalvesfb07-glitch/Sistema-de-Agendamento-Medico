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
            agora.toLocaleTimeString("pt-BR");

    }

}


atualizarDataHora();

setInterval(
    atualizarDataHora,
    1000
);



// ======================================
// ELEMENTOS
// ======================================

var search =
    document.getElementById("patientSearch");

var patientsList =
    document.getElementById("patientsList");

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
// USUÁRIO LOGADO
// ======================================

var usuarioLogado =
    JSON.parse(
        localStorage.getItem(
            "usuarioLogado"
        )
    );



// ======================================
// VERIFICAR LOGIN
// ======================================

if (!usuarioLogado) {

    window.location.href =
        "../login/login.html";

}



// ======================================
// FUNÇÃO PARA PEGAR USUÁRIOS
// ======================================

function obterUsuarios() {

    var usuarios = [];


    try {

        usuarios =
            JSON.parse(
                localStorage.getItem(
                    "clinicaUsuarios"
                )
            ) || [];

    } catch (erro) {

        console.error(
            "Erro ao ler clinicaUsuarios:",
            erro
        );

        usuarios = [];

    }


    return usuarios;

}



// ======================================
// NORMALIZAR PERFIL
// ======================================

function normalizarPerfil(tipo) {

    if (!tipo) {

        return "";

    }


    return String(tipo)
        .normalize("NFD")
        .replace(
            /[\u0300-\u036f]/g,
            ""
        )
        .toLowerCase()
        .trim();

}



// ======================================
// OBTER PACIENTES
// ======================================

function obterPacientes() {

    var pacientes = [];


    // ==================================
    // PACIENTES DO ADMINISTRADOR
    // ==================================

    var usuarios =
        obterUsuarios();


    usuarios.forEach(
        function(usuario) {

            var tipo =
                normalizarPerfil(
                    usuario.tipo ||
                    usuario.role ||
                    usuario.perfil ||
                    usuario.perfilUsuario
                );


            if (
                tipo === "paciente"
            ) {

                pacientes.push({

                    id:
                        usuario.id ||
                        Date.now().toString(),

                    nome:
                        usuario.nome ||
                        usuario.name ||
                        "Paciente",

                    email:
                        usuario.email ||
                        "",

                    cpf:
                        usuario.cpf ||
                        usuario.CPF ||
                        "",

                    nascimento:
                        usuario.nascimento ||
                        usuario.birthDate ||
                        usuario.dataNascimento ||
                        "",

                    telefone:
                        usuario.telefone ||
                        usuario.phone ||
                        "",

                    endereco:
                        usuario.endereco ||
                        usuario.address ||
                        "",

                    ultimaConsulta:
                        usuario.ultimaConsulta ||
                        "Nenhuma",

                    origem:
                        "administrador"

                });

            }

        }
    );



    // ==================================
    // PACIENTES DA RECEPÇÃO
    // ==================================

    var pacientesRecepcao =
        [];


    try {

        pacientesRecepcao =
            JSON.parse(
                localStorage.getItem(
                    "pacientesCadastrados"
                )
            ) || [];

    } catch (erro) {

        console.error(
            "Erro ao ler pacientesCadastrados:",
            erro
        );

        pacientesRecepcao = [];

    }


    pacientesRecepcao.forEach(
        function(paciente) {

            pacientes.push({

                id:
                    paciente.id ||
                    Date.now().toString(),

                nome:
                    paciente.nome ||
                    paciente.name ||
                    "Paciente",

                email:
                    paciente.email ||
                    "",

                cpf:
                    paciente.cpf ||
                    paciente.CPF ||
                    "",

                nascimento:
                    paciente.nascimento ||
                    paciente.birthDate ||
                    paciente.dataNascimento ||
                    "",

                telefone:
                    paciente.telefone ||
                    paciente.phone ||
                    "",

                endereco:
                    paciente.endereco ||
                    paciente.address ||
                    "",

                ultimaConsulta:
                    paciente.ultimaConsulta ||
                    "Nenhuma",

                origem:
                    "recepcao"

            });

        }
    );



    // ==================================
    // REMOVER DUPLICADOS
    // ==================================

    var pacientesUnicos = [];


    pacientes.forEach(
        function(paciente) {

            var cpf =
                String(
                    paciente.cpf || ""
                )
                .replace(
                    /\D/g,
                    ""
                );


            var email =
                String(
                    paciente.email || ""
                )
                .toLowerCase()
                .trim();


            var nome =
                String(
                    paciente.nome || ""
                )
                .toLowerCase()
                .trim();


            var duplicado =
                pacientesUnicos.some(
                    function(item) {

                        var itemCpf =
                            String(
                                item.cpf || ""
                            )
                            .replace(
                                /\D/g,
                                ""
                            );


                        var itemEmail =
                            String(
                                item.email || ""
                            )
                            .toLowerCase()
                            .trim();


                        var itemNome =
                            String(
                                item.nome || ""
                            )
                            .toLowerCase()
                            .trim();


                        // Mesmo CPF
                        if (
                            cpf &&
                            itemCpf &&
                            cpf === itemCpf
                        ) {

                            return true;

                        }


                        // Mesmo e-mail
                        if (
                            email &&
                            itemEmail &&
                            email === itemEmail
                        ) {

                            return true;

                        }


                        // Mesmo nome quando não há
                        // CPF nem e-mail
                        if (
                            !cpf &&
                            !email &&
                            !itemCpf &&
                            !itemEmail &&
                            nome === itemNome
                        ) {

                            return true;

                        }


                        return false;

                    }
                );


            if (
                !duplicado
            ) {

                pacientesUnicos.push(
                    paciente
                );

            }

        }
    );


    return pacientesUnicos;

}



// ======================================
// INICIAIS
// ======================================

function obterIniciais(nome) {

    if (!nome) {

        return "PA";

    }


    return nome
        .trim()
        .split(" ")
        .filter(Boolean)
        .slice(0, 2)
        .map(
            function(parte) {

                return parte
                    .charAt(0)
                    .toUpperCase();

            }
        )
        .join("");

}



// ======================================
// FORMATAR CPF
// ======================================

function formatarCPF(cpf) {

    if (!cpf) {

        return "Não informado";

    }


    var numeros =
        String(cpf)
            .replace(
                /\D/g,
                ""
            );


    if (
        numeros.length !== 11
    ) {

        return cpf;

    }


    return numeros.replace(
        /(\d{3})(\d{3})(\d{3})(\d{2})/,
        "$1.$2.$3-$4"
    );

}



// ======================================
// FORMATAR DATA
// ======================================

function formatarData(data) {

    if (!data) {

        return "Não informado";

    }


    // Já está no formato brasileiro
    if (
        /^\d{2}\/\d{2}\/\d{4}$/.test(
            data
        )
    ) {

        return data;

    }


    // Formato YYYY-MM-DD
    if (
        /^\d{4}-\d{2}-\d{2}$/.test(
            data
        )
    ) {

        var partes =
            data.split("-");


        return (
            partes[2] +
            "/" +
            partes[1] +
            "/" +
            partes[0]
        );

    }


    return data;

}



// ======================================
// RENDERIZAR PACIENTES
// ======================================

function renderizarPacientes() {

    if (!patientsList) {

        return;

    }


    var pacientes =
        obterPacientes();


    patientsList.innerHTML =
        "";


    if (
        pacientes.length === 0
    ) {

        if (noResults) {

            noResults.style.display =
                "block";

            noResults.textContent =
                "Nenhum paciente cadastrado.";

        }

        return;

    }


    if (noResults) {

        noResults.style.display =
            "none";

    }


    pacientes.forEach(
        function(paciente) {

            var nome =
                paciente.nome ||
                "Paciente";


            var cpf =
                paciente.cpf ||
                "";


            var nascimento =
                paciente.nascimento ||
                "";


            var telefone =
                paciente.telefone ||
                "";


            var ultimaConsulta =
                paciente.ultimaConsulta ||
                "Nenhuma";


            var row =
                document.createElement(
                    "article"
                );


            row.className =
                "patient-row";


            row.setAttribute(
                "data-name",
                nome
            );


            row.setAttribute(
                "data-cpf",
                String(cpf)
                    .replace(
                        /\D/g,
                        ""
                    )
            );


            row.innerHTML = `

                <div class="patient-person">

                    <div class="patient-avatar">
                        ${obterIniciais(nome)}
                    </div>

                    <div>

                        <strong>
                            ${nome}
                        </strong>

                        <span>
                            CPF: ${formatarCPF(cpf)}
                        </span>

                    </div>

                </div>


                <div class="patient-info">

                    <span>
                        Nascimento
                    </span>

                    <strong>
                        ${formatarData(nascimento)}
                    </strong>

                </div>


                <div class="patient-info">

                    <span>
                        Telefone
                    </span>

                    <strong>
                        ${telefone || "Não informado"}
                    </strong>

                </div>


                <div class="patient-info">

                    <span>
                        Última consulta
                    </span>

                    <strong>
                        ${ultimaConsulta}
                    </strong>

                </div>


                <button
                    class="view-button"
                    type="button"
                >
                    Visualizar
                </button>

            `;


            var botao =
                row.querySelector(
                    ".view-button"
                );


            if (botao) {

                botao.addEventListener(
                    "click",
                    function() {

                        visualizarPaciente(
                            paciente
                        );

                    }
                );

            }


            patientsList.appendChild(
                row
            );

        }
    );


    aplicarPesquisa();

}



// ======================================
// PESQUISA
// ======================================

function aplicarPesquisa() {

    if (!search) {

        return;

    }


    var texto =
        search.value
            .toLowerCase()
            .trim();


    var numero =
        search.value
            .replace(
                /\D/g,
                ""
            );


    var rows =
        document.querySelectorAll(
            ".patient-row"
        );


    var encontrados = 0;


    rows.forEach(
        function(row) {

            var nome =
                (
                    row.getAttribute(
                        "data-name"
                    ) || ""
                )
                .toLowerCase();


            var cpf =
                row.getAttribute(
                    "data-cpf"
                ) || "";


            if (
                nome.includes(texto) ||
                (
                    numero &&
                    cpf.includes(numero)
                ) ||
                (
                    !texto &&
                    !numero
                )
            ) {

                row.style.display =
                    "grid";

                encontrados++;

            } else {

                row.style.display =
                    "none";

            }

        }
    );


    if (
        encontrados === 0
    ) {

        if (noResults) {

            noResults.style.display =
                "block";

            noResults.textContent =
                "Nenhum paciente encontrado.";

        }

    } else {

        if (noResults) {

            noResults.style.display =
                "none";

        }

    }

}


if (search) {

    search.addEventListener(
        "input",
        aplicarPesquisa
    );

}



// ======================================
// ABRIR MODAL
// ======================================

function abrirModal() {

    if (!modal) {

        return;

    }


    modal.classList.add(
        "show"
    );


    var nome =
        document.getElementById(
            "fullName"
        );


    if (nome) {

        nome.focus();

    }

}


if (newButton) {

    newButton.addEventListener(
        "click",
        abrirModal
    );

}



// ======================================
// FECHAR MODAL
// ======================================

function fecharModal() {

    if (!modal) {

        return;

    }


    modal.classList.remove(
        "show"
    );

}


if (closeButton) {

    closeButton.addEventListener(
        "click",
        fecharModal
    );

}


if (cancelButton) {

    cancelButton.addEventListener(
        "click",
        fecharModal
    );

}


if (modal) {

    modal.addEventListener(
        "click",
        function(event) {

            if (
                event.target === modal
            ) {

                fecharModal();

            }

        }
    );

}



// ======================================
// MÁSCARA CPF
// ======================================

var cpfInput =
    document.getElementById(
        "cpf"
    );


if (cpfInput) {

    cpfInput.addEventListener(
        "input",
        function() {

            var value =
                this.value
                    .replace(
                        /\D/g,
                        ""
                    )
                    .slice(
                        0,
                        11
                    );


            if (
                value.length > 9
            ) {

                value =
                    value.replace(
                        /(\d{3})(\d{3})(\d{3})(\d{1,2})/,
                        "$1.$2.$3-$4"
                    );

            } else if (
                value.length > 6
            ) {

                value =
                    value.replace(
                        /(\d{3})(\d{3})(\d{1,3})/,
                        "$1.$2.$3"
                    );

            } else if (
                value.length > 3
            ) {

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

}



// ======================================
// MÁSCARA TELEFONE
// ======================================

var phoneInput =
    document.getElementById(
        "phone"
    );


if (phoneInput) {

    phoneInput.addEventListener(
        "input",
        function() {

            var value =
                this.value
                    .replace(
                        /\D/g,
                        ""
                    )
                    .slice(
                        0,
                        11
                    );


            if (
                value.length > 10
            ) {

                value =
                    value.replace(
                        /(\d{2})(\d{5})(\d{4})/,
                        "($1) $2-$3"
                    );

            } else if (
                value.length > 6
            ) {

                value =
                    value.replace(
                        /(\d{2})(\d{4})(\d{1,4})/,
                        "($1) $2-$3"
                    );

            } else if (
                value.length > 2
            ) {

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

}



// ======================================
// CADASTRAR PACIENTE PELA RECEPÇÃO
// ======================================

if (form) {

    form.addEventListener(
        "submit",
        function(event) {

            event.preventDefault();


            var name =
                document
                    .getElementById(
                        "fullName"
                    )
                    .value
                    .trim();


            var cpf =
                document
                    .getElementById(
                        "cpf"
                    )
                    .value
                    .trim();


            var birth =
                document
                    .getElementById(
                        "birthDate"
                    )
                    .value;


            var phone =
                document
                    .getElementById(
                        "phone"
                    )
                    .value
                    .trim();


            var email =
                document
                    .getElementById(
                        "email"
                    )
                    .value
                    .trim();


            var address =
                document
                    .getElementById(
                        "address"
                    )
                    .value
                    .trim();



            // ==============================
            // BUSCAR PACIENTES EXISTENTES
            // ==============================

            var pacientesExistentes =
                obterPacientes();


            var cpfNumeros =
                cpf.replace(
                    /\D/g,
                    ""
                );


            var emailNormalizado =
                email
                    .toLowerCase()
                    .trim();


            var duplicado =
                pacientesExistentes.some(
                    function(paciente) {

                        var pacienteCpf =
                            String(
                                paciente.cpf || ""
                            )
                            .replace(
                                /\D/g,
                                ""
                            );


                        var pacienteEmail =
                            String(
                                paciente.email || ""
                            )
                            .toLowerCase()
                            .trim();


                        if (
                            cpfNumeros &&
                            pacienteCpf &&
                            cpfNumeros ===
                            pacienteCpf
                        ) {

                            return true;

                        }


                        if (
                            emailNormalizado &&
                            pacienteEmail &&
                            emailNormalizado ===
                            pacienteEmail
                        ) {

                            return true;

                        }


                        return false;

                    }
                );


            if (
                duplicado
            ) {

                alert(
                    "Já existe um paciente cadastrado com este CPF ou e-mail."
                );

                return;

            }



            // ==============================
            // LISTA DA RECEPÇÃO
            // ==============================

            var pacientesRecepcao =
                JSON.parse(
                    localStorage.getItem(
                        "pacientesCadastrados"
                    )
                ) || [];


            var patient = {

                id:
                    Date.now().toString(),

                nome:
                    name,

                cpf:
                    cpf,

                nascimento:
                    birth,

                telefone:
                    phone,

                email:
                    email,

                endereco:
                    address,

                ultimaConsulta:
                    "Nenhuma",

                origem:
                    "recepcao"

            };


            pacientesRecepcao.push(
                patient
            );


            localStorage.setItem(
                "pacientesCadastrados",
                JSON.stringify(
                    pacientesRecepcao
                )
            );


            alert(
                "Paciente cadastrado com sucesso!"
            );


            form.reset();

            fecharModal();


            renderizarPacientes();

        }
    );

}



// ======================================
// VISUALIZAR PACIENTE
// ======================================

function visualizarPaciente(paciente) {

    localStorage.setItem(
        "pacienteSelecionado",
        JSON.stringify(
            paciente
        )
    );


    var nome =
        paciente.nome ||
        paciente.name ||
        "Paciente";


    alert(
        "Paciente selecionado: " +
        nome
    );

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
        function() {

            localStorage.removeItem(
                "usuarioLogado"
            );


            localStorage.removeItem(
                "pacienteLogado"
            );


            window.location.href =
                "../login/login.html";

        }
    );

}



// ======================================
// ATUALIZAR AO VOLTAR PARA A PÁGINA
// ======================================

window.addEventListener(
    "focus",
    function() {

        renderizarPacientes();

    }
);



// ======================================
// ATUALIZAR QUANDO O STORAGE MUDAR
// ======================================

window.addEventListener(
    "storage",
    function(event) {

        if (
            event.key ===
            "clinicaUsuarios" ||
            event.key ===
            "pacientesCadastrados"
        ) {

            renderizarPacientes();

        }

    }
);



// ======================================
// INICIAR
// ======================================

renderizarPacientes();
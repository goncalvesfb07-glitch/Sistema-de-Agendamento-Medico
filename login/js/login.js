var form = document.getElementById("loginForm");
var senha = document.getElementById("senha");
var botao = document.getElementById("togglePassword");
var mensagem = document.getElementById("loginMessage");


// ===============================
// MOSTRAR / OCULTAR SENHA
// ===============================

botao.onclick = function () {

    if (senha.type === "password") {

        senha.type = "text";
        botao.textContent = "🙈";

    } else {

        senha.type = "password";
        botao.textContent = "👁";

    }

};


// ===============================
// LOGIN
// ===============================

form.onsubmit = function (event) {

    event.preventDefault();

    var usuarioDigitado =
        document.getElementById("usuario").value.trim();

    var senhaDigitada =
        senha.value;


    // ===============================
    // LOGINS FIXOS DE TESTE
    // ===============================

    if (
        usuarioDigitado === "medico" &&
        senhaDigitada === "1234"
    ) {

        realizarLogin(
            {
                id: "medico",
                nome: "Médico",
                email: "medico",
                tipo: "medico",
                senha: "1234",
                status: "Ativo"
            },
            "medico/dashboard.html"
        );

        return;
    }


    if (
        usuarioDigitado === "recepcionista" &&
        senhaDigitada === "1234"
    ) {

        realizarLogin(
            {
                id: "recepcionista",
                nome: "Recepcionista",
                email: "recepcionista",
                tipo: "recepcionista",
                senha: "1234",
                status: "Ativo"
            },
            "recepcionista/dashboard.html"
        );

        return;
    }


    if (
        usuarioDigitado === "paciente" &&
        senhaDigitada === "1234"
    ) {

        realizarLogin(
            {
                id: "paciente",
                nome: "Paciente",
                email: "paciente",
                tipo: "paciente",
                senha: "1234",
                status: "Ativo"
            },
            "paciente/dashboard.html"
        );

        return;
    }


    if (
        usuarioDigitado === "admin" &&
        senhaDigitada === "1234"
    ) {

        realizarLogin(
            {
                id: "admin",
                nome: "Administrador",
                email: "admin",
                tipo: "administrador",
                senha: "1234",
                status: "Ativo"
            },
            "administrador/dashboard.html"
        );

        return;
    }


    // ===============================
    // USUÁRIOS CADASTRADOS
    // PELO ADMINISTRADOR
    // ===============================

    var usuarios =
        JSON.parse(
            localStorage.getItem("clinicaUsuarios")
        ) || [];


    // Caso ainda exista a lista antiga
    if (usuarios.length === 0) {

        usuarios =
            JSON.parse(
                localStorage.getItem("usuarios")
            ) || [];

    }


    // Procura pelo e-mail ou nome de usuário
    var usuarioEncontrado =
        usuarios.find(function (user) {

            var email =
                String(
                    user.email || ""
                ).trim().toLowerCase();

            var nome =
                String(
                    user.nome ||
                    user.name ||
                    ""
                ).trim().toLowerCase();

            var usuario =
                String(
                    user.usuario ||
                    user.username ||
                    ""
                ).trim().toLowerCase();

            var digitado =
                usuarioDigitado.toLowerCase();


            return (
                email === digitado ||
                nome === digitado ||
                usuario === digitado
            );

        });


    // ===============================
    // USUÁRIO NÃO EXISTE
    // ===============================

    if (!usuarioEncontrado) {

        mensagem.textContent =
            "Usuário ou senha incorretos.";

        mensagem.style.color = "red";

        return;

    }


    // ===============================
    // VERIFICA STATUS
    // ===============================

    if (
        usuarioEncontrado.status &&
        usuarioEncontrado.status !== "Ativo"
    ) {

        mensagem.textContent =
            "Este usuário está inativo.";

        mensagem.style.color = "red";

        return;

    }


    // ===============================
    // VERIFICA SENHA
    // ===============================

    var senhaCorreta =
        usuarioEncontrado.senha ||
        usuarioEncontrado.password ||
        "";


    if (
        senhaDigitada !==
        String(senhaCorreta)
    ) {

        mensagem.textContent =
            "Usuário ou senha incorretos.";

        mensagem.style.color = "red";

        return;

    }


    // ===============================
    // IDENTIFICA O PERFIL
    // ===============================

    var tipo =
        String(
            usuarioEncontrado.tipo ||
            usuarioEncontrado.role ||
            usuarioEncontrado.perfil ||
            "paciente"
        )
        .toLowerCase()
        .trim();


    if (
        tipo === "admin"
    ) {

        tipo = "administrador";

    }


    // ===============================
    // DEFINE A PÁGINA
    // ===============================

    var pagina;


    if (tipo === "medico") {

        pagina =
            "medico/dashboard.html";

    } else if (tipo === "recepcionista") {

        pagina =
            "recepcionista/dashboard.html";

    } else if (tipo === "administrador") {

        pagina =
            "administrador/dashboard.html";

    } else {

        pagina =
            "paciente/dashboard.html";

    }


    // ===============================
    // REALIZA LOGIN
    // ===============================

    realizarLogin(
        usuarioEncontrado,
        pagina
    );

};


// ===============================
// FUNÇÃO DE LOGIN
// ===============================

function realizarLogin(
    usuario,
    pagina
) {

    mensagem.textContent =
        "Login realizado com sucesso!";

    mensagem.style.color =
        "green";


    // Salva quem está logado
    localStorage.setItem(
        "usuarioLogado",
        JSON.stringify(usuario)
    );


    setTimeout(function () {

        window.location.href =
            pagina;

    }, 500);

}
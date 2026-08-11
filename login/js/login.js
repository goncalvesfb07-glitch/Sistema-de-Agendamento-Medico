```javascript
var form = document.getElementById("loginForm");
var senha = document.getElementById("senha");
var botao = document.getElementById("togglePassword");
var mensagem = document.getElementById("loginMessage");

botao.onclick = function () {
    if (senha.type === "password") {
        senha.type = "text";
        botao.textContent = "🙈";
    } else {
        senha.type = "password";
        botao.textContent = "👁";
    }
};

form.onsubmit = function (event) {
    event.preventDefault();

    var usuario = document.getElementById("usuario").value;
    var senhaDigitada = senha.value;

    if (usuario === "medico" && senhaDigitada === "1234") {
        mensagem.textContent = "Login realizado com sucesso!";
        mensagem.style.color = "green";

        setTimeout(function () {
            window.location.href = "medico/dashboard.html";
        }, 500);

        return;
    }

    if (usuario === "recepcionista" && senhaDigitada === "1234") {
        mensagem.textContent = "Login realizado com sucesso!";
        mensagem.style.color = "green";

        setTimeout(function () {
            window.location.href = "recepcionista/dashboard.html";
        }, 500);

        return;
    }

    if (usuario === "paciente" && senhaDigitada === "1234") {
        mensagem.textContent = "Login realizado com sucesso!";
        mensagem.style.color = "green";

        setTimeout(function () {
            window.location.href = "paciente/dashboard.html";
        }, 500);

        return;
    }

    if (usuario === "admin" && senhaDigitada === "1234") {
        mensagem.textContent = "Login realizado com sucesso!";
        mensagem.style.color = "green";

        setTimeout(function () {
            window.location.href = "administrador/dashboard.html";
        }, 500);

        return;
    }

    mensagem.textContent = "Usuário ou senha incorretos.";
    mensagem.style.color = "red";
};
```

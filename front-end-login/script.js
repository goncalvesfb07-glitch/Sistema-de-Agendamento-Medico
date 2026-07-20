const form = document.getElementById("loginForm");

form.addEventListener("submit", function(e){

    e.preventDefault();

    const perfil = document.getElementById("perfil").value;

    if(perfil==""){
        alert("Selecione um perfil.");
        return;
    }

    switch(perfil){

        case "medico":
            alert("Login realizado como Médico");
            // window.location = "medico/dashboard.html";
            break;

        case "recepcionista":
            alert("Login realizado como Recepcionista");
            // window.location = "recepcionista/dashboard.html";
            break;

        case "paciente":
            alert("Login realizado como Paciente");
            // window.location = "paciente/dashboard.html";
            break;

    }

});

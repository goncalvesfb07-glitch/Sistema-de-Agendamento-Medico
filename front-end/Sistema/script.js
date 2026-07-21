const form = document.getElementById("loginForm");

form.addEventListener("submit", function(e){

    e.preventDefault();

    const perfil = document.getElementById("perfil").value;

    if(perfil===""){

        alert("Selecione um perfil.");

        return;

    }

    switch(perfil){

        case "medico":

            window.location.href = "medico/dashboard.html";

            break;

        case "recepcionista":

            alert("Área da Recepcionista em desenvolvimento.");

            break;

        case "paciente":

            alert("Área do Paciente em desenvolvimento.");

            break;

    }

});
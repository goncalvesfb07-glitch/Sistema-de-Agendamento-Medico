// ===============================
// RELÓGIO
// ===============================

function atualizarRelogio() {

    const agora = new Date();

    document.getElementById("hora").innerHTML =
        agora.toLocaleTimeString("pt-BR");

    document.getElementById("data").innerHTML =
        agora.toLocaleDateString("pt-BR", {

            weekday: "long",
            day: "2-digit",
            month: "long",
            year: "numeric"

        });

}

setInterval(atualizarRelogio,1000);

atualizarRelogio();


// ===============================
// DADOS TEMPORÁRIOS
// ===============================

const medico = {

    nome:"Dr. João Silva",

    especialidade:"Cardiologia"

};

document.getElementById("nomeMedico").innerHTML=medico.nome;


// ===============================
// STATUS DAS CONSULTAS
// ===============================

const status=document.querySelectorAll(".status");

status.forEach(select=>{

    select.addEventListener("change",()=>{

        alert("Status alterado para: "+select.value);

    });

});


// ===============================
// BOTÕES
// ===============================

const botoes=document.querySelectorAll(".btnAtender");

botoes.forEach(botao=>{

    botao.addEventListener("click",()=>{

        window.location="prontuarios.html";

    });

});
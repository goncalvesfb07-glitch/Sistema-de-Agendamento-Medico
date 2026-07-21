function atualizarRelogio(){

    const agora=new Date();

    document.getElementById("hora").innerHTML=
    agora.toLocaleTimeString("pt-BR");

}

setInterval(atualizarRelogio,1000);

atualizarRelogio();

const data=new Date();

document.getElementById("dataAgenda").innerHTML=
data.toLocaleDateString("pt-BR",{

weekday:"long",

day:"2-digit",

month:"long",

year:"numeric"

});

const botoes=document.querySelectorAll("button");

botoes.forEach(botao=>{

botao.onclick=()=>{

window.location="prontuarios.html";

}

});
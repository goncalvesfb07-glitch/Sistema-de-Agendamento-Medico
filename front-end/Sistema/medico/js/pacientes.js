const pesquisa=document.querySelector("input");

pesquisa.addEventListener("keyup",()=>{

const texto=pesquisa.value.toLowerCase();

const pacientes=document.querySelectorAll(".pacienteCard");

pacientes.forEach(card=>{

const nome=card.innerText.toLowerCase();

if(nome.includes(texto))

card.style.display="flex";

else

card.style.display="none";

});

});

const abrir=document.querySelectorAll(".abrir");

abrir.forEach(botao=>{

botao.onclick=()=>{

window.location="prontuarios.html";

}

});
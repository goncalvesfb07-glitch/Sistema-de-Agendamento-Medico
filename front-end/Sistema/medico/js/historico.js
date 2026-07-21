const pesquisa=document.querySelector("input");

pesquisa.addEventListener("keyup",()=>{

const valor=pesquisa.value.toLowerCase();

const linhas=document.querySelectorAll("tbody tr");

linhas.forEach(linha=>{

if(linha.innerText.toLowerCase().includes(valor))

linha.style.display="";

else

linha.style.display="none";

});

});


const abrir=document.querySelectorAll(".abrir");

abrir.forEach(botao=>{

botao.onclick=()=>{

alert("Abrindo prontuário...");

window.location="prontuarios.html";

}

});
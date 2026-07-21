const salvar=document.querySelector(".btnSalvar");

salvar.addEventListener("click",()=>{

alert("Prontuário salvo com sucesso!");

});


const finalizar=document.querySelector(".btnFinalizar");

finalizar.addEventListener("click",()=>{

const resposta=confirm("Deseja finalizar o atendimento?");

if(resposta){

alert("Consulta finalizada.");

window.location="historico.html";

}

});
function normalizar(texto){
return texto
.toLowerCase()
.normalize("NFD")
.replace(/[\u0300-\u036f]/g,"");
}

const buscador = document.querySelector(".search");

buscador.addEventListener("input",()=>{

const valor = normalizar(buscador.value);

const cards = document.querySelectorAll(".card");

cards.forEach(card=>{

const texto = normalizar(card.innerText);

if(texto.includes(valor)){
card.style.display="block";
}else{
card.style.display="none";
}

});

});
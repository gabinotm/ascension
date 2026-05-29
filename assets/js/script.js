/* =========================================================
NORMALIZAR TEXTO
========================================================= */

function normalizar(texto){

    return texto
    .toLowerCase()
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g,"");

}

/* =========================================================
BUSCADOR CARDS
========================================================= */

const buscador =
document.querySelector(".search");

if(buscador){

    buscador.addEventListener("input",()=>{

        const valor =
        normalizar(buscador.value);

        const cards =
        document.querySelectorAll(".card");

        cards.forEach(card=>{

            const texto =
            normalizar(card.innerText);

            if(texto.includes(valor)){

                card.style.display="block";

            }else{

                card.style.display="none";

            }

        });

    });

}

/* =========================================================
FILTRO TABLAS
========================================================= */

const searchInput =
document.getElementById('searchInput');

const filterCategoria =
document.getElementById('filterCategoria');

function filterTable(){

    if(!searchInput || !filterCategoria) return;

    const text =
    searchInput.value.toLowerCase();

    const categoria =
    filterCategoria.value.toLowerCase();

    const rows =
    document.querySelectorAll('tbody tr');

    rows.forEach(row => {

        const contenido =
        row.innerText.toLowerCase();

        const categoriaRow =
        row.children[3]
        ? row.children[3].innerText.toLowerCase()
        : '';

        const matchText =
        contenido.includes(text);

        const matchCategoria =
        categoria == "" ||
        categoriaRow == categoria;

        if(matchText && matchCategoria){

            row.style.display = '';

        }else{

            row.style.display = 'none';

        }

    });

}

if(searchInput){

    searchInput.addEventListener(
        'keyup',
        filterTable
    );

}

if(filterCategoria){

    filterCategoria.addEventListener(
        'change',
        filterTable
    );

}

/* =========================================================
MENU MOBILE
========================================================= */

document.addEventListener('DOMContentLoaded',()=>{

    const menuToggle =
    document.getElementById('menuToggle');

    const navbar =
    document.getElementById('navbar');

    if(menuToggle && navbar){

        menuToggle.addEventListener('click',()=>{

            navbar.classList.toggle('active');

        });

    }

    /* =========================================================
    SUBMENUS MOBILE
    ========================================================= */

    const dropdowns =
    document.querySelectorAll('.dropdown');

    dropdowns.forEach(drop=>{

        const button =
        drop.querySelector('.dropbtn');

        if(button){

            button.addEventListener('click',(e)=>{

                if(window.innerWidth <= 768){

                    e.preventDefault();

                    const isActive =
                    drop.classList.contains('active');

                    /* CERRAR TODOS */

                    dropdowns.forEach(other=>{

                        other.classList.remove('active');

                    });

                    /* ABRIR SOLO SI ESTABA CERRADO */

                    if(!isActive){

                        drop.classList.add('active');

                    }

                }

            });

        }

    });

});

/* =========================
CONFIRMAR GUARDAR
========================= */

function confirmSave(event){

event.preventDefault();

Swal.fire({

title:'¿Guardar cambios?',

text:'Se guardará la información del usuario',

icon:'question',

showCancelButton:true,

confirmButtonColor:'#004aad',

cancelButtonColor:'#d33',

confirmButtonText:'Sí, guardar',

cancelButtonText:'Cancelar'

}).then((result)=>{

if(result.isConfirmed){

document.getElementById('userForm').submit();

}

});

return false;

}
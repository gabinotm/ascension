
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

const calendarDays =
document.getElementById('calendarDays');

const calendarMonth =
document.getElementById('calendarMonth');

const now = new Date();

const year = now.getFullYear();
const month = now.getMonth();

const months = [
'Enero','Febrero','Marzo','Abril','Mayo','Junio',
'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'
];

calendarMonth.innerHTML =
months[month] + ' ' + year;

const firstDay =
new Date(year,month,1).getDay();

const totalDays =
new Date(year,month+1,0).getDate();

let html = '';

let startDay = firstDay === 0 ? 6 : firstDay - 1;

for(let i=0;i<startDay;i++){

html += `
<div class="calendar-day-empty"></div>
`;

}

const eventos =
Array.from(document.querySelectorAll('.calendar-event-item'))
.map(item=> item.dataset.fecha);

for(let day=1; day<=totalDays; day++){

const fechaActual =
`${year}-${String(month+1).padStart(2,'0')}-${String(day).padStart(2,'0')}`;

const tieneEvento =
eventos.includes(fechaActual);

const hoy =
now.getDate() === day;

html += `

<div class="calendar-day-modern ${tieneEvento ? 'has-event' : ''} ${hoy ? 'today' : ''}">

${day}

${tieneEvento ? '<span class="event-indicator"></span>' : ''}

</div>

`;

}

calendarDays.innerHTML = html;

</script>
<!-- =========================================================
FOOTER PREMIUM
========================================================= -->

<footer class="footer-modern">

<div class="footer-overlay"></div>

<div class="footer-wrapper">

<div class="row g-5">

<!-- =========================================================
COLUMNA 1
========================================================= -->

<div class="col-lg-4">

<div class="footer-brand">

<h2>

SISAPPWEB

</h2>

<p>

Plataforma moderna de gestión educativa,
comunicación institucional y aprendizaje digital.

</p>

</div>

</div>

<!-- =========================================================
COLUMNA 2
========================================================= -->

<div class="col-lg-4">

<h5 class="footer-title">

Accesos directos

</h5>

<div class="footer-shortcuts">

<a
href="https://learn.libredu.com/login"
target="_blank"
class="footer-shortcut"
>

<img
src="https://app.libredu.com/assets/images/logo/logo-white.png"
alt="LIBREDU"
loading="lazy"
>

<span>

LIBREDU

</span>

</a>

<a
href="https://app.micoleg.com/avantgard/"
target="_blank"
class="footer-shortcut"
>

<img
src="https://play-lh.googleusercontent.com/YWqDErPA3I906kkQ62eTYV4G3XVvWzeyE1NPFI1F5hYsuBR0ORuAmebvCKbGt0ynyQ"
alt="MICOLEG"
loading="lazy"
>

<span>

MICOLEG

</span>

</a>
<a
href="https://www.cambridgeone.org/login"
target="_blank"
class="footer-shortcut"
>

<img
src="https://www.shutterstock.com/image-vector/university-cambridge-logo-vector-600nw-1845651232.jpg"
alt="CAMBRIDGE"
loading="lazy"
>

<span>

CAMBRIDGE

</span>

</a>
<a
href="https://classroom.google.com/"
target="_blank"
class="footer-shortcut"
>

<img
src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQnJOqlO4XuevYMqsIK9Kbd0Ey7a2LaBsp_NQ&s"
alt="CLASSROOM"
loading="lazy"
>

<span>

CLASSROOM

</span>

</a>
</div>

</div>

<!-- =========================================================
COLUMNA 3
========================================================= -->

<div class="col-lg-4">

<h5 class="footer-title">

Navegación

</h5>

<div class="footer-links">

<a href="index.php">

Inicio

</a>

<a href="#publicaciones">

Anuncios

</a>

<a href="login.php">

Iniciar sesión

</a>

</div>

<div class="footer-social">

<a
href="https://sisappweb.com/"
target="_blank"
>

<i class="bi bi-globe"></i>

</a>

<a
href="https://facebook.com/"
target="_blank"
>

<i class="bi bi-facebook"></i>

</a>

<a
href="https://instagram.com/"
target="_blank"
>

<i class="bi bi-instagram"></i>

</a>

</div>

</div>

</div>

<!-- =========================================================
COPYRIGHT
========================================================= -->

<div class="footer-copy">

© <?php echo date('Y'); ?>

Todos los derechos reservados |

Desarrollado por

<a
href="https://sisappweb.com/"
target="_blank"
>

SISAPPWEB.COM

</a>

</div>

</div>

</footer>
<script>

if('serviceWorker' in navigator){

navigator.serviceWorker.register('/colegio-ascension/sw.js')

.then(() => {

console.log('Service Worker registrado');

});

}

</script>
<script>

/* =========================================================
LIVE SEARCH
========================================================= */

const globalSearch =
document.getElementById(
'globalSearch'
);

const globalResults =
document.getElementById(
'globalSearchResults'
);

globalSearch.addEventListener(
'keyup',
function(){

const texto =
this.value.trim();

if(texto.length < 2){

globalResults.style.display='none';

globalResults.innerHTML='';

return;

}

fetch(

'<?php echo BASE_URL; ?>includes/search_live.php?q='
+ texto

)

.then(response=>response.text())

.then(data=>{

globalResults.innerHTML = data;

globalResults.style.display='block';

});

}
);

/* =========================================================
CERRAR
========================================================= */

document.addEventListener(
'click',
function(e){

if(
!e.target.closest('.global-search-wrapper')
){

globalResults.style.display='none';

}

}
);

</script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
</body>
</html>
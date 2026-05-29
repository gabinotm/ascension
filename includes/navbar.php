<?php

require_once __DIR__.'/../config/conexion.php';
require_once __DIR__.'/../config/app.php';

if(session_status() === PHP_SESSION_NONE){
session_start();
}

/* =========================================================
ROLES
========================================================= */

function esAdministrador(){
return isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 1;
}

function esDirector(){
return isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 2;
}

function esDocente(){
return isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 3;
}

function esTice(){
return isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 4;
}

/* =========================================================
LOGIN
========================================================= */

$logueado = isset($_SESSION['id']);

/* =========================================================
CATEGORÍAS
========================================================= */

$queryMenu = "

SELECT *

FROM categorias

WHERE estado='Activo'

ORDER BY id ASC

";

$resultMenu = mysqli_query(
$conn,
$queryMenu
);

?>

<nav
class="navbar navbar-expand-lg navbar-modern"
>

<div class="container-fluid navbar-container-custom">

<!-- =====================================================
LOGO
===================================================== -->

<a
class="navbar-brand d-flex align-items-center"
href="<?php echo BASE_URL; ?>index.php"
>

<img
src="<?php echo BASE_URL; ?>img/logo.png"
alt="Logo"
class="navbar-logo"
>

</a>

<!-- =====================================================
BUSCADOR GLOBAL
===================================================== -->

<div class="global-search-wrapper">

<div class="global-search-box">

<i class="bi bi-search"></i>

<input
type="text"
id="globalSearch"
placeholder="Buscar..."
autocomplete="off"
>

</div>

<!-- RESULTADOS -->

<div
class="global-search-results"
id="globalSearchResults"
>

</div>

</div>

<!-- =====================================================
BOTON MENU MOBILE
===================================================== -->

<button
class="navbar-toggler"
type="button"
data-bs-toggle="collapse"
data-bs-target="#navbarMain"
aria-controls="navbarMain"
aria-expanded="false"
aria-label="Toggle navigation"
>

<span class="navbar-toggler-icon"></span>

</button>

<!-- =====================================================
MENU
===================================================== -->

<div
class="collapse navbar-collapse navbar-collapse-custom"
id="navbarMain"
>

<?php if($logueado){ ?>

<!-- =====================================================
MENU PRIVADO
===================================================== -->

<ul class="navbar-nav navbar-menu-custom">

<!-- INICIO -->

<li class="nav-item">

<a
class="nav-link"
href="<?php echo BASE_URL; ?>index.php"
>

Inicio

</a>

</li>

<!-- =====================================================
MENU DINAMICO
===================================================== -->

<?php while($menu = mysqli_fetch_assoc($resultMenu)){ ?>

<li class="nav-item dropdown">

<a
class="nav-link dropdown-toggle"
href="#"
role="button"
data-bs-toggle="dropdown"
aria-expanded="false"
>

<?php echo $menu['nombre']; ?>

</a>

<ul class="dropdown-menu shadow border-0 rounded-4 p-2">

<?php

$idCategoria = $menu['id'];

$querySub = "

SELECT *

FROM subcategorias

WHERE categoria_id='$idCategoria'

AND estado='Activo'

ORDER BY id ASC

";

$resultSub = mysqli_query(
$conn,
$querySub
);

while($sub = mysqli_fetch_assoc($resultSub)){

?>

<li>

<a
class="dropdown-item rounded-3"
href="<?php echo BASE_URL; ?>modulo.php?subcategoria=<?php echo $sub['id']; ?>"
>

<?php echo $sub['nombre']; ?>

</a>

</li>

<?php } ?>

</ul>

</li>

<?php } ?>

<!-- =====================================================
ADMIN
===================================================== -->

<?php if(!esDocente()){ ?>

<li class="nav-item dropdown">

<a
class="nav-link dropdown-toggle"
href="#"
role="button"
data-bs-toggle="dropdown"
aria-expanded="false"
>

Admin

</a>

<ul class="dropdown-menu shadow border-0 rounded-4 p-2">

<!-- PUBLICACIONES -->

<li>

<a
class="dropdown-item rounded-3"
href="<?php echo BASE_URL; ?>admin/publicar.php"
>

Publicaciones

</a>

</li>

<!-- SOLO ADMIN Y TICE -->

<?php if(
esAdministrador()
||
esTice()
){ ?>

<li>

<a
class="dropdown-item rounded-3"
href="<?php echo BASE_URL; ?>admin/categorias.php"
>

Categorías

</a>

</li>

<li>

<a
class="dropdown-item rounded-3"
href="<?php echo BASE_URL; ?>admin/usuarios.php"
>

Usuarios

</a>

</li>

<?php } ?>

</ul>

</li>

<?php } ?>

</ul>

<!-- =====================================================
RIGHT AREA
===================================================== -->

<div class="navbar-right-area">

<!-- =====================================================
USER
===================================================== -->

<div class="navbar-user-area">

<div class="navbar-user">

<?php echo $_SESSION['nombres']; ?>

</div>

<a
href="<?php echo BASE_URL; ?>logout.php"
class="btn btn-light rounded-pill px-4 fw-semibold"
>

Salir

</a>

</div>

</div>

<?php }else{ ?>

<!-- =====================================================
VISITANTE
===================================================== -->

<ul class="navbar-nav ms-auto">

<li class="nav-item">

<a
class="nav-link active"
href="<?php echo BASE_URL; ?>index.php"
>

Inicio

</a>

</li>

</ul>

<div class="ms-lg-4 mt-3 mt-lg-0">

<a
href="<?php echo BASE_URL; ?>login.php"
class="btn btn-light rounded-pill px-4 fw-semibold"
>

Iniciar Sesión

</a>

</div>

<?php } ?>

</div>

</div>

</nav>
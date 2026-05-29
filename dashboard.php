<?php

session_start();

include 'includes/auth.php';

/* =========================================================
VALIDAR ROLES
========================================================= */

if(

$_SESSION['rol_id'] != 1

&&

$_SESSION['rol_id'] != 2

&&

$_SESSION['rol_id'] != 4

){

header('Location: index.php');
exit;

}

/* =========================================================
CONEXION
========================================================= */

require_once 'config/conexion.php';


include 'includes/header.php';

include 'includes/navbar.php';

/* =========================================================
ESTADISTICAS
========================================================= */

$totalPublicaciones = mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT COUNT(*) total FROM publicaciones"
)
)['total'];

$totalUsuarios = mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT COUNT(*) total FROM usuarios"
)
)['total'];

$totalCategorias = mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT COUNT(*) total FROM categorias"
)
)['total'];

$totalSubcategorias = mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT COUNT(*) total FROM subcategorias"
)
)['total'];

$totalEventos = mysqli_fetch_assoc(
mysqli_query(
$conn,
"
SELECT COUNT(*) total
FROM publicaciones
WHERE tipo_publicacion='eventos'
"
)
)['total'];

$totalReuniones = mysqli_fetch_assoc(
mysqli_query(
$conn,
"
SELECT COUNT(*) total
FROM publicaciones
WHERE tipo_publicacion='reuniones'
"
)
)['total'];

/* =========================================================
ULTIMAS PUBLICACIONES
========================================================= */

$queryUltimas = "

SELECT publicaciones.*,

usuarios.usuario AS autor

FROM publicaciones

LEFT JOIN usuarios
ON publicaciones.usuario_id = usuarios.id

ORDER BY publicaciones.id DESC

LIMIT 6

";

$resultUltimas = mysqli_query(
$conn,
$queryUltimas
);

/* =========================================================
EVENTOS PROXIMOS
========================================================= */

$queryEventos = "

SELECT *

FROM publicaciones

WHERE
(

tipo_publicacion='eventos'

OR

tipo_publicacion='reuniones'

OR

tipo_publicacion='actividades'

)

AND fecha_inicio >= CURDATE()

ORDER BY fecha_inicio ASC, hora ASC

LIMIT 5

";

$resultEventos = mysqli_query(
$conn,
$queryEventos
);

?>

<div class="main-container">

<!-- =========================================================
HEADER
========================================================= -->

<div class="dashboard-header-modern mb-5">

<div>

<h1 class="dashboard-title-modern">

Dashboard Institucional

</h1>

<p class="dashboard-subtitle-modern">

Gestión educativa moderna y monitoreo institucional

</p>

</div>

<div class="dashboard-actions">

<a
href="publicar.php"
class="dashboard-btn-primary"
>

<i class="bi bi-plus-circle-fill"></i>

Nueva publicación

</a>

</div>

</div>

<!-- =========================================================
STATS
========================================================= -->

<div class="row g-4 mb-5">

<div class="col-xl-2 col-lg-4 col-md-6">

<div class="dashboard-stat-card">

<div class="dashboard-stat-icon bg-primary">

<i class="bi bi-megaphone-fill"></i>

</div>

<div>

<h3>

<?php echo $totalPublicaciones; ?>

</h3>

<p>

Publicaciones

</p>

</div>

</div>

</div>

<div class="col-xl-2 col-lg-4 col-md-6">

<div class="dashboard-stat-card">

<div class="dashboard-stat-icon bg-success">

<i class="bi bi-people-fill"></i>

</div>

<div>

<h3>

<?php echo $totalUsuarios; ?>

</h3>

<p>

Usuarios

</p>

</div>

</div>

</div>

<div class="col-xl-2 col-lg-4 col-md-6">

<div class="dashboard-stat-card">

<div class="dashboard-stat-icon bg-warning">

<i class="bi bi-grid-fill"></i>

</div>

<div>

<h3>

<?php echo $totalCategorias; ?>

</h3>

<p>

Categorías

</p>

</div>

</div>

</div>

<div class="col-xl-2 col-lg-4 col-md-6">

<div class="dashboard-stat-card">

<div class="dashboard-stat-icon bg-info">

<i class="bi bi-diagram-3-fill"></i>

</div>

<div>

<h3>

<?php echo $totalSubcategorias; ?>

</h3>

<p>

Subcategorías

</p>

</div>

</div>

</div>

<div class="col-xl-2 col-lg-4 col-md-6">

<div class="dashboard-stat-card">

<div class="dashboard-stat-icon bg-danger">

<i class="bi bi-calendar-event-fill"></i>

</div>

<div>

<h3>

<?php echo $totalEventos; ?>

</h3>

<p>

Eventos

</p>

</div>

</div>

</div>

<div class="col-xl-2 col-lg-4 col-md-6">

<div class="dashboard-stat-card">

<div class="dashboard-stat-icon bg-dark">

<i class="bi bi-camera-video-fill"></i>

</div>

<div>

<h3>

<?php echo $totalReuniones; ?>

</h3>

<p>

Reuniones

</p>

</div>

</div>

</div>

</div>

<!-- =========================================================
CONTENT
========================================================= -->

<div class="row g-4">

<!-- =========================================================
ULTIMAS PUBLICACIONES
========================================================= -->

<div class="col-xl-8">

<div class="dashboard-card-modern">

<div class="dashboard-card-header">

<h4>

Últimas publicaciones

</h4>

<a href="publicar.php">

Ver todo

</a>

</div>

<div class="table-responsive">

<table class="table dashboard-table-modern align-middle">

<thead>

<tr>

<th>Título</th>
<th>Tipo</th>
<th>Autor</th>
<th>Fecha</th>
<th>Estado</th>

</tr>

</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($resultUltimas)){ ?>

<tr>

<td>

<div class="d-flex align-items-center gap-3">

<?php if($row['imagen'] != ''){ ?>

<img
src="../img/<?php echo $row['imagen']; ?>"
class="dashboard-table-img"
>

<?php }else{ ?>

<div class="dashboard-table-placeholder">

<i class="bi bi-image"></i>

</div>

<?php } ?>

<div>

<h6 class="mb-1 fw-bold">

<?php echo $row['titulo']; ?>

</h6>

<small class="text-secondary">

ID #<?php echo $row['id']; ?>

</small>

</div>

</div>

</td>

<td>

<span class="dashboard-badge">

<?php echo ucfirst($row['tipo_publicacion']); ?>

</span>

</td>

<td>

<?php echo $row['autor'] ?? 'Administrador'; ?>

</td>

<td>

<?php

echo date(
'd/m/Y h:i A',
strtotime($row['fecha_registro'])
);

?>

</td>

<td>

<span class="dashboard-status-active">

Publicado

</span>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

<!-- =========================================================
SIDEBAR
========================================================= -->

<div class="col-xl-4">

<!-- EVENTOS -->

<div class="dashboard-card-modern mb-4">

<div class="dashboard-card-header">

<h4>

Próximos eventos

</h4>

</div>

<div class="dashboard-events-list">

<?php while($evento = mysqli_fetch_assoc($resultEventos)){ ?>

<div class="dashboard-event-item">

<div class="dashboard-event-date">

<?php echo date('d',strtotime($evento['fecha_inicio'])); ?>

<span>

<?php echo date('M',strtotime($evento['fecha_inicio'])); ?>

</span>

</div>

<div>

<h6>

<?php echo $evento['titulo']; ?>

</h6>

<p>

<?php echo ucfirst($evento['tipo_publicacion']); ?>

•

<?php echo $evento['hora']; ?>

</p>

</div>

</div>

<?php } ?>

</div>

</div>

<!-- ACCESOS -->

<div class="dashboard-card-modern">

<div class="dashboard-card-header">

<h4>

Accesos rápidos

</h4>

</div>

<div class="dashboard-shortcuts-grid">

<a href="publicar.php" class="dashboard-shortcut-item">

<i class="bi bi-megaphone-fill"></i>

<span>

Publicaciones

</span>

</a>

<a href="categorias.php" class="dashboard-shortcut-item">

<i class="bi bi-grid-fill"></i>

<span>

Categorías

</span>

</a>

<a href="subcategorias.php" class="dashboard-shortcut-item">

<i class="bi bi-diagram-3-fill"></i>

<span>

Subcategorías

</span>

</a>

<a href="usuarios.php" class="dashboard-shortcut-item">

<i class="bi bi-people-fill"></i>

<span>

Usuarios

</span>

</a>

</div>

</div>

</div>

</div>

</div>

<style>

/* =========================================================
HEADER
========================================================= */

.dashboard-header-modern{

display:flex;

justify-content:space-between;

align-items:center;

gap:20px;

flex-wrap:wrap;

}

.dashboard-title-modern{

font-size:42px;

font-weight:900;

margin-bottom:10px;

color:#0f172a;

}

.dashboard-subtitle-modern{

font-size:16px;

color:#64748b;

margin:0;

}

.dashboard-btn-primary{

height:54px;

padding:0 24px;

border-radius:18px;

background:linear-gradient(
135deg,
#2563eb,
#1d4ed8
);

color:#fff;

font-weight:700;

text-decoration:none;

display:inline-flex;

align-items:center;

gap:10px;

box-shadow:0 15px 35px rgba(37,99,235,.25);

}

/* =========================================================
STAT CARDS
========================================================= */

.dashboard-stat-card{

background:#fff;

border-radius:26px;

padding:24px;

display:flex;

align-items:center;

gap:18px;

height:100%;

box-shadow:0 12px 35px rgba(15,23,42,.06);

}

.dashboard-stat-icon{

width:64px;
height:64px;

border-radius:20px;

color:#fff;

font-size:24px;

display:flex;

align-items:center;

justify-content:center;

}

.dashboard-stat-card h3{

font-size:34px;

font-weight:900;

margin:0;

color:#0f172a;

}

.dashboard-stat-card p{

margin:0;

color:#64748b;

font-weight:600;

}

/* =========================================================
CARD
========================================================= */

.dashboard-card-modern{

background:#fff;

border-radius:28px;

padding:28px;

box-shadow:0 12px 35px rgba(15,23,42,.06);

height:100%;

}

.dashboard-card-header{

display:flex;

justify-content:space-between;

align-items:center;

margin-bottom:24px;

}

.dashboard-card-header h4{

font-size:22px;

font-weight:800;

margin:0;

}

.dashboard-card-header a{

text-decoration:none;

font-weight:700;

color:#2563eb;

}

/* =========================================================
TABLE
========================================================= */

.dashboard-table-modern thead th{

border:none;

font-size:13px;

text-transform:uppercase;

color:#64748b;

padding-bottom:18px;

}

.dashboard-table-modern tbody td{

border:none;

padding:18px 0;

vertical-align:middle;

}

.dashboard-table-img{

width:62px;
height:62px;

border-radius:18px;

object-fit:cover;

}

.dashboard-table-placeholder{

width:62px;
height:62px;

border-radius:18px;

background:#eff6ff;

color:#2563eb;

font-size:24px;

display:flex;

align-items:center;

justify-content:center;

}

.dashboard-badge{

padding:10px 16px;

border-radius:999px;

background:#eff6ff;

color:#2563eb;

font-size:12px;

font-weight:700;

}

.dashboard-status-active{

padding:10px 16px;

border-radius:999px;

background:#dcfce7;

color:#166534;

font-size:12px;

font-weight:700;

}

/* =========================================================
EVENTOS
========================================================= */

.dashboard-events-list{

display:flex;

flex-direction:column;

gap:18px;

}

.dashboard-event-item{

display:flex;

align-items:center;

gap:16px;

padding:18px;

border-radius:20px;

background:#f8fafc;

}

.dashboard-event-date{

min-width:70px;
height:70px;

border-radius:20px;

background:linear-gradient(
135deg,
#2563eb,
#1d4ed8
);

color:#fff;

font-size:24px;

font-weight:900;

display:flex;

flex-direction:column;

align-items:center;

justify-content:center;

line-height:1;

}

.dashboard-event-date span{

font-size:12px;

margin-top:6px;

}

.dashboard-event-item h6{

font-weight:800;

margin-bottom:6px;

}

.dashboard-event-item p{

margin:0;

font-size:14px;

color:#64748b;

}

/* =========================================================
SHORTCUTS
========================================================= */

.dashboard-shortcuts-grid{

display:grid;

grid-template-columns:repeat(2,1fr);

gap:16px;

}

.dashboard-shortcut-item{

height:120px;

border-radius:24px;

background:#f8fafc;

text-decoration:none;

color:#0f172a;

display:flex;

flex-direction:column;

align-items:center;

justify-content:center;

gap:12px;

transition:.3s;

}

.dashboard-shortcut-item:hover{

background:#2563eb;

color:#fff;

transform:translateY(-4px);

}

.dashboard-shortcut-item i{

font-size:28px;

}

.dashboard-shortcut-item span{

font-weight:700;

}

/* =========================================================
RESPONSIVE
========================================================= */

@media(max-width:768px){

.dashboard-title-modern{

font-size:32px;

}

.dashboard-stat-card{

padding:20px;

}

.dashboard-card-modern{

padding:22px;

}

.dashboard-shortcuts-grid{

grid-template-columns:1fr;

}

}

</style>

<?php include 'includes/footer.php'; ?>
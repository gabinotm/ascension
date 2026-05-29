<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
include '../includes/auth.php';

require_once '../config/conexion.php';

$basePath = '../';

/* =========================================================
FUNCION YOUTUBE
========================================================= */

function obtenerYoutubeID($url){

$patrones = [

'/youtube\.com\/watch\?v=([^\&\?\/]+)/',

'/youtube\.com\/embed\/([^\&\?\/]+)/',

'/youtube\.com\/shorts\/([^\&\?\/]+)/',

'/youtu\.be\/([^\&\?\/]+)/'

];

foreach($patrones as $patron){

if(preg_match($patron,$url,$matches)){

return $matches[1];

}

}

return '';

}
/* =========================================================
OBTENER GOOGLE DRIVE ID
========================================================= */

function obtenerDriveID($url){

preg_match(
'/\/d\/(.*?)\//',
$url,
$matches
);

return $matches[1] ?? '';

}

/* =========================================================
GUARDAR
========================================================= */

if(isset($_POST['guardar'])){

$titulo = mysqli_real_escape_string(
$conn,
$_POST['titulo']
);

$descripcion = mysqli_real_escape_string(
$conn,
$_POST['descripcion']
);

$subcategoria_id = $_POST['subcategoria_id'];

$tipo_publicacion = $_POST['tipo_publicacion'];

$link = $_POST['link'] ?? '';

$video = $_POST['video'] ?? '';

$fecha_inicio = $_POST['fecha_inicio'] ?? '';

$fecha_fin = $_POST['fecha_fin'] ?? '';

$hora = $_POST['hora'] ?? '';

$prioridad = $_POST['prioridad'] ?? '';

$usuario_id = $_SESSION['id'];

/* =========================================================
IMAGEN
========================================================= */

$imagen = '';

if(
isset($_FILES['imagen']) &&
$_FILES['imagen']['name'] != ''
){

$nombreImagen =
time().'_'.$_FILES['imagen']['name'];

move_uploaded_file(

$_FILES['imagen']['tmp_name'],

'../img/'.$nombreImagen

);

$imagen = $nombreImagen;

}

/* =========================================================
ARCHIVO
========================================================= */

$archivo = '';

if(
isset($_FILES['archivo']) &&
$_FILES['archivo']['name'] != ''
){

$nombreArchivo =
time().'_'.$_FILES['archivo']['name'];

move_uploaded_file(

$_FILES['imagen']['tmp_name'],

'../img/'.$nombreArchivo

);

$archivo = $nombreArchivo;

}

/* =========================================================
INSERT
========================================================= */

$query = "

INSERT INTO publicaciones
(

titulo,
descripcion,
imagen,
archivo,
video,
link,
fecha_inicio,
fecha_fin,
hora,
prioridad,
tipo_publicacion,
subcategoria_id,
usuario_id

)

VALUES
(

'$titulo',
'$descripcion',
'$imagen',
'$archivo',
'$video',
'$link',
'$fecha_inicio',
'$fecha_fin',
'$hora',
'$prioridad',
'$tipo_publicacion',
'$subcategoria_id',
'$usuario_id'

)

";

$guardar = mysqli_query(
$conn,
$query
);

/* =========================================================
ERROR MYSQL
========================================================= */

if(!$guardar){

die(

'Error SQL: '

.

mysqli_error($conn)

);

}

/* =========================================================
REDIRECT
========================================================= */

header(
'Location: publicar.php?success=1'
);

exit;

}

/* =========================================================
EDITAR
========================================================= */

if(isset($_POST['editar_publicacion'])){

$id = $_POST['id_publicacion'];

$titulo = mysqli_real_escape_string(
$conn,
$_POST['titulo']
);

$descripcion = mysqli_real_escape_string(
$conn,
$_POST['descripcion']
);
$subcategoria_id =
$_POST['subcategoria_id'];

$tipo_publicacion =
$_POST['tipo_publicacion'];
$link = $_POST['link'] ?? '';

$video = $_POST['video'] ?? '';

$fecha_inicio = $_POST['fecha_inicio'] ?? '';

$fecha_fin = $_POST['fecha_fin'] ?? '';

$hora = $_POST['hora'] ?? '';

$prioridad = $_POST['prioridad'] ?? '';

/* =========================================================
IMAGEN
========================================================= */

$imagenSQL = '';

if(
isset($_FILES['imagen'])
&&
$_FILES['imagen']['name'] != ''
){

$nombreImagen =
time().'_'.$_FILES['imagen']['name'];

move_uploaded_file(
$_FILES['imagen']['tmp_name'],
'../img/'.$nombreImagen
);

$imagenSQL =
", imagen='$nombreImagen'";

}

/* =========================================================
ARCHIVO
========================================================= */

$archivoSQL = '';

if(
isset($_FILES['archivo'])
&&
$_FILES['archivo']['name'] != ''
){

$nombreArchivo =
time().'_'.$_FILES['archivo']['name'];

move_uploaded_file(
$_FILES['archivo']['tmp_name'],
'../img/'.$nombreArchivo
);

$archivoSQL =
", archivo='$nombreArchivo'";

}

/* =========================================================
UPDATE
========================================================= */

$queryUpdate = "

UPDATE publicaciones

SET

titulo='$titulo',
descripcion='$descripcion',
subcategoria_id='$subcategoria_id',
tipo_publicacion='$tipo_publicacion',
link='$link',
video='$video',
fecha_inicio='$fecha_inicio',
fecha_fin='$fecha_fin',
hora='$hora',
prioridad='$prioridad'

$imagenSQL

$archivoSQL

WHERE id='$id'

";

$actualizar = mysqli_query(
$conn,
$queryUpdate
);

/* =========================================================
ERROR MYSQL
========================================================= */

if(!$actualizar){

die(mysqli_error($conn));

}

header('Location: publicar.php?update=1');
exit;

}

/* =========================================================
ELIMINAR
========================================================= */

if(isset($_GET['eliminar'])){

$id = $_GET['eliminar'];

mysqli_query(
$conn,
"
DELETE FROM publicaciones
WHERE id='$id'
"
);

header('Location: publicar.php');
exit;

}

/* =========================================================
SUBCATEGORIAS
========================================================= */

$querySubcategorias = "

SELECT subcategorias.*,

categorias.nombre AS categoria

FROM subcategorias

LEFT JOIN categorias
ON subcategorias.categoria_id = categorias.id


WHERE subcategorias.estado='Activo'

ORDER BY categorias.nombre ASC

";

$resultSubcategorias =
mysqli_query(
$conn,
$querySubcategorias
);

/* =========================================================
LISTAR
========================================================= */

$queryPublicaciones = "

SELECT publicaciones.*,

subcategorias.nombre AS subcategoria,

categorias.nombre AS categoria,

usuarios.nombres AS autor

FROM publicaciones

LEFT JOIN subcategorias
ON publicaciones.subcategoria_id = subcategorias.id

LEFT JOIN categorias
ON subcategorias.categoria_id = categorias.id

LEFT JOIN usuarios
ON publicaciones.usuario_id = usuarios.id

ORDER BY publicaciones.id DESC

";

$resultPublicaciones =
mysqli_query(
$conn,
$queryPublicaciones
);

/* =========================================================
HEADER
========================================================= */

include '../includes/header.php';

include '../includes/navbar.php';

?>
<?php if(isset($_GET['success'])){ ?>

<script>

Swal.fire({

icon:'success',
title:'Publicación creada correctamente'

});

</script>

<?php } ?>

<?php if(isset($_GET['update'])){ ?>

<script>

Swal.fire({

icon:'success',
title:'Publicación actualizada'

});

</script>

<?php } ?>

<?php if(isset($_GET['delete'])){ ?>

<script>

Swal.fire({

icon:'success',
title:'Publicación eliminada'

});

</script>

<?php } ?>

<div class="main-container">

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">

<div>

<h1 class="page-title mb-0" style="font-size: 24px; font-weight: 700;">

Publicaciones

</h1>

<p class="page-subtitle mb-0" style="font-size: 13px;">

CMS dinámico institucional

</p>

</div>

<button
class="btn-custom btn-primary-custom"
data-bs-toggle="modal"
data-bs-target="#modalPublicacion"
style="height: 36px; padding: 0 16px; font-size: 14px; border-radius: 8px;"
>

+ Nueva Publicación

</button>

</div>

<!-- =========================================================
BARRA DE HERRAMIENTAS: COMPACTA
========================================================= -->
<div class="card-custom mb-3 p-2">
    
    <div class="row g-2 align-items-center">
        <!-- Input Buscador -->
        <div class="col-12 col-md-6 col-lg-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white border-end-0" style="border-radius: 8px 0 0 8px; height: 36px;">
                    <i class="bi bi-search text-secondary"></i>
                </span>
                <input type="text" id="buscarPublicacion" class="form-control border-start-0" placeholder="Buscar título, categoría..." style="border-radius: 0 8px 8px 0; height: 36px; font-size: 14px;">
            </div>
        </div>
        <!-- Selector de Tipo -->
        <div class="col-12 col-md-6 col-lg-3">
            <select id="filtroTipoRealTime" class="form-select form-select-sm" style="border-radius: 8px; height: 36px; font-size: 14px;">
                <option value="todos">Todos los tipos</option>
                <option value="herramientas">Herramientas</option>
                <option value="actividades">Actividades</option>
                <option value="eventos">Eventos</option>
                <option value="galeria">Galería</option>
                <option value="archivos">Archivo</option>
                <option value="tutoriales">Tutorial</option>
                <option value="alertas">Alerta</option>
                <option value="informativo">Informativo</option>
                <option value="reuniones">Reunión</option>
            </select>
        </div>
    </div>
</div>

<div class="card-custom p-0 overflow-hidden">

<div class="table-responsive">

<table class="table-custom dynamic-table-compact" id="tablaPublicaciones">

<thead>

<tr>

<th>Título</th>
<th>Categoría</th>
<th>Subcategoría</th>
<th>Tipo</th>
<th>Fecha</th>
<th>Acciones</th>

</tr>

</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($resultPublicaciones)){ ?>

<tr data-tipo="<?php echo strtolower($row['tipo_publicacion']); ?>">

<td>
<strong><?php echo $row['titulo']; ?></strong>
</td>

<td>
<?php echo $row['categoria']; ?>
</td>

<td>
<?php echo $row['subcategoria']; ?>
</td>

<td>
<span class="badge bg-light text-dark border px-2 py-1" style="font-size: 11px;">
    <?php echo ucfirst($row['tipo_publicacion']); ?>
</span>
</td>

<td>
<?php echo date('d/m/Y', strtotime($row['fecha_registro'])); ?>
</td>

<td>

<div class="d-flex gap-1">

<!-- VER -->

<button
class="btn-custom btn-primary-custom btn-compact-action"
data-bs-toggle="modal"
data-bs-target="#verPublicacion<?php echo $row['id']; ?>"
>

Ver

</button>

<!-- EDITAR -->

<button
class="btn-custom btn-warning-custom btn-compact-action"
data-bs-toggle="modal"
data-bs-target="#editarPublicacion<?php echo $row['id']; ?>"
>

Editar

</button>

<!-- ELIMINAR -->

<button
onclick="eliminarPublicacion(
'publicar.php?eliminar=<?php echo $row['id']; ?>'
)"
class="btn-custom btn-info-custom btn-compact-action"
>

Eliminar

</button>

</div>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>
<?php

mysqli_data_seek($resultPublicaciones,0);

while($row = mysqli_fetch_assoc($resultPublicaciones)){

?>
<!-- MODAL VER -->

<div
class="modal fade"
id="verPublicacion<?php echo $row['id']; ?>"
tabindex="-1"
>

<div class="modal-dialog modal-md modal-dialog-centered">

<div class="modal-content border-0 rounded-3 overflow-hidden">

<div class="modal-header py-2 px-3 border-bottom">

<h5 class="modal-title fw-bold" style="font-size: 16px;">

Vista previa publicación

</h5>

<button
class="btn-close"
data-bs-dismiss="modal"
></button>

</div>

<div class="modal-body bg-light p-3">

<div class="card-custom p-3 m-0">

<?php if($row['imagen'] != ''){ ?>

<img
src="../img/<?php echo $row['imagen']; ?>"
class="img-fluid rounded-3 mb-3"
style="
height:160px;
object-fit:cover;
width:100%;
"
>

<?php } ?>

<div class="mb-2">
    <div class="d-flex flex-wrap gap-2 mb-2">

<div class="text-secondary small" style="font-size: 12px;">

<i class="bi bi-person-fill"></i>

<?php echo $row['autor']; ?>

</div>

<div class="text-secondary small" style="font-size: 12px;">

<i class="bi bi-clock-fill"></i>

<?php echo date(
'd/m/Y h:i A',
strtotime($row['fecha_registro'])
); ?>

</div>

</div>

<span class="badge bg-primary px-2 py-1" style="font-size: 11px;">

<?php echo ucfirst($row['tipo_publicacion']); ?>

</span>

</div>

<h3 class="fw-bold mb-2" style="font-size: 18px;">

<?php echo $row['titulo']; ?>

</h3>

<p
class="text-secondary mb-3"
style="
font-size:14px;
line-height:1.5;
"
>

<?php echo nl2br($row['descripcion']); ?>
<?php

if(
$row['tipo_publicacion']=='eventos'
||
$row['tipo_publicacion']=='reuniones'
||
$row['tipo_publicacion']=='actividades'
){

?>

<div
class="countdown-box mt-2"
data-fecha="<?php echo $row['fecha_inicio'].' '.$row['hora']; ?>"
id="countdown<?php echo $row['id']; ?>"
>

Cargando cuenta regresiva...

</div>

<?php } ?>
</p>

<div class="d-flex flex-wrap gap-2">

<?php if($row['link'] != ''){ ?>

<a
href="<?php echo $row['link']; ?>"
target="_blank"
class="btn btn-sm btn-primary px-3"
style="border-radius:6px;"
>

Abrir Herramienta

</a>

<?php } ?>

<?php if($row['archivo'] != ''){ ?>

<a
href="../img/<?php echo $row['archivo']; ?>"
target="_blank"
class="btn btn-sm btn-outline-primary px-3"
style="border-radius:6px;"
>

Ver Archivo

</a>

<?php } ?>

<?php if($row['video'] != ''){ ?>

<button
class="btn btn-sm btn-danger px-3"
data-bs-toggle="modal"
data-bs-target="#videoPreview<?php echo $row['id']; ?>"
style="border-radius:6px;"
>

Ver Tutorial

</button>

<?php } ?>

</div>

</div>

</div>

</div>

</div>

</div>

<!-- MODAL VIDEO -->

<div
class="modal fade"
id="videoPreview<?php echo $row['id']; ?>"
tabindex="-1"
>

<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable custom-modal-publicacion">

<div class="modal-content border-0 rounded-3 overflow-hidden">

<div class="modal-header py-2 px-3">

<h5 class="modal-title fw-bold" style="font-size:15px;">

<?php echo $row['titulo']; ?>

</h5>

<button
class="btn-close"
data-bs-dismiss="modal"
></button>

</div>

<div class="modal-body p-0">

<div class="ratio ratio-16x9">

<?php

$video = $row['video'];

if(

strpos($video,'youtube.com') !== false

||

strpos($video,'youtu.be') !== false

){

$video = trim($row['video']);

if(

strpos($video,'youtube.com') !== false

||

strpos($video,'youtu.be') !== false

){

$youtubeID = obtenerYoutubeID($video);

?>

<iframe
src="https://www.youtube.com/embed/<?php echo $youtubeID; ?>"
title="YouTube video player"
frameborder="0"
allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
allowfullscreen
style="
width:100%;
height:100%;
border:0;
"
></iframe>

<?php } ?>

<?php

if(
strpos($video,'drive.google.com') !== false
){

preg_match(
'/\/d\/(.*?)\//',
$video,
$matches
);

$driveID = $matches[1] ?? '';

?>

<video
controls
class="w-100"
style="
max-height:500px;
background:#000;
"
>

<source
src="https://drive.google.com/uc?export=download&id=<?php echo $driveID; ?>"
type="video/mp4"
>

</video>

<?php } ?>

<?php } ?>

<?php

if(

strpos($video,'drive.google.com') !== false

){

?>

<iframe
src="https://drive.google.com/file/d/<?php echo obtenerDriveID($video); ?>/preview"
allowfullscreen
></iframe>

<?php } ?>

</div>

</div>

</div>

</div>

</div>

<!-- MODAL EDITAR -->

<div
class="modal fade"
id="editarPublicacion<?php echo $row['id']; ?>"
tabindex="-1"
>

<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable custom-modal-publicacion">

<div class="modal-content border-0 rounded-3 overflow-hidden">

<div class="modal-header py-2 px-3">

<h5 class="modal-title fw-bold" style="font-size:15px;">

Editar Publicación

</h5>

<button
class="btn-close"
data-bs-dismiss="modal"
></button>

</div>

<form
method="POST"
enctype="multipart/form-data"
>

<div class="modal-body">

<input
type="hidden"
name="id_publicacion"
value="<?php echo $row['id']; ?>"
>

<div class="row g-2">

<!-- TITULO -->

<div class="col-lg-6">

<label class="form-label">

Título

</label>

<input
type="text"
name="titulo"
class="form-control"
value="<?php echo $row['titulo']; ?>"
required
>

</div>

<!-- SUBCATEGORIA -->

<div class="col-lg-6">

<label class="form-label">

Categoría / Subcategoría

</label>

<select
name="subcategoria_id"
class="form-select"
required
>

<?php

mysqli_data_seek($resultSubcategorias,0);

while($sub = mysqli_fetch_assoc($resultSubcategorias)){

?>

<option
value="<?php echo $sub['id']; ?>"

<?php
if($row['subcategoria_id']==$sub['id']){
echo 'selected';
}
?>

>

<?php echo $sub['categoria']; ?>

→

<?php echo $sub['nombre']; ?>

</option>

<?php } ?>

</select>

</div>

<!-- TIPO -->

<div class="col-lg-6">

<label class="form-label">

Tipo

</label>

<select
name="tipo_publicacion"
class="form-select"
required
>

<option
value="herramientas"
<?php if($row['tipo_publicacion']=='herramientas'){ echo 'selected'; } ?>
>

Herramientas

</option>

<option
value="actividades"
<?php if($row['tipo_publicacion']=='actividades'){ echo 'selected'; } ?>
>

Actividades

</option>

<option
value="eventos"
<?php if($row['tipo_publicacion']=='eventos'){ echo 'selected'; } ?>
>

Eventos

</option>

<option
value="galeria"
<?php if($row['tipo_publicacion']=='galeria'){ echo 'selected'; } ?>
>

Galería

</option>

<option
value="archivos"
<?php if($row['tipo_publicacion']=='archivos'){ echo 'selected'; } ?>
>

Archivo

</option>

<option
value="tutoriales"
<?php if($row['tipo_publicacion']=='tutoriales'){ echo 'selected'; } ?>
>

Tutorial

</option>

<option
value="alertas"
<?php if($row['tipo_publicacion']=='alertas'){ echo 'selected'; } ?>
>

Alerta

</option>

<option
value="informativo"
<?php if($row['tipo_publicacion']=='informativo'){ echo 'selected'; } ?>
>

Informativo

</option>

<option
value="reuniones"
<?php if($row['tipo_publicacion']=='reuniones'){ echo 'selected'; } ?>
>

Reunión

</option>

</select>

</div>

<div class="col-12">

<label class="form-label">

Portada / Banner (Opcional)

</label>

<input
type="file"
name="imagen"
class="form-control"
>

<?php if($row['imagen'] != ''){ ?>

<img
src="../img/<?php echo $row['imagen']; ?>"
class="img-fluid rounded-2 mt-2"
style="
width:120px;
height:80px;
object-fit:cover;
"
>

<?php } ?>

</div>
<div class="col-12">

<label class="form-label">

Descripción

</label>

<textarea
name="descripcion"
class="form-control"
rows="3"
><?php echo $row['descripcion']; ?></textarea>

</div>

<!-- DINAMICOS EDITAR -->

<?php if($row['tipo_publicacion']=='herramientas'){ ?>

<div class="col-lg-6">

<label class="form-label">

Link

</label>

<input
type="text"
name="link"
class="form-control"
value="<?php echo $row['link']; ?>"
>

</div>

<?php } ?>

<?php if($row['tipo_publicacion']=='eventos'){ ?>

<div class="col-lg-4">

<label class="form-label">

Fecha inicio

</label>

<input
type="date"
name="fecha_inicio"
class="form-control"
value="<?php echo $row['fecha_inicio']; ?>"
>

</div>

<div class="col-lg-4">

<label class="form-label">

Fecha final

</label>

<input
type="date"
name="fecha_fin"
class="form-control"
value="<?php echo $row['fecha_fin']; ?>"
>

</div>

<div class="col-lg-4">

<label class="form-label">

Hora

</label>

<input
type="time"
name="hora"
class="form-control"
value="<?php echo $row['hora']; ?>"
>

</div>

<?php } ?>

<?php if($row['tipo_publicacion']=='archivos'){ ?>

<div class="col-12">

<label class="form-label">

Archivo

</label>

<input
type="file"
name="archivo"
class="form-control"
>

</div>

<?php } ?>

<?php if($row['tipo_publicacion']=='tutoriales'){ ?>

<div class="col-lg-6">

<label class="form-label">

Video YouTube

</label>

<input
type="text"
name="video"
class="form-control"
value="<?php echo $row['video']; ?>"
>

</div>

<div class="col-lg-6">

<label class="form-label">

PDF

</label>

<input
type="file"
name="archivo"
class="form-control"
>

</div>

<?php } ?>

<?php if($row['tipo_publicacion']=='alertas'){ ?>

<div class="col-lg-6">

<label class="form-label">

Prioridad

</label>

<select
name="prioridad"
class="form-select"
>

<option
value="Alta"
<?php if($row['prioridad']=='Alta'){ echo 'selected'; } ?>
>

Alta

</option>

<option
value="Media"
<?php if($row['prioridad']=='Media'){ echo 'selected'; } ?>
>

Media

</option>

<option
value="Baja"
<?php if($row['prioridad']=='Baja'){ echo 'selected'; } ?>
>

Baja

</option>

</select>

</div>

<?php } ?>

<?php if($row['tipo_publicacion']=='reuniones'){ ?>

<div class="col-lg-6">

<label class="form-label">

Fecha

</label>

<input
type="date"
name="fecha_inicio"
class="form-control"
value="<?php echo $row['fecha_inicio']; ?>"
>

</div>

<div class="col-lg-6">

<label class="form-label">

Hora

</label>

<input
type="time"
name="hora"
class="form-control"
value="<?php echo $row['hora']; ?>"
>

</div>

<?php } ?>

</div>

</div>

<div class="modal-footer py-2 px-3">

<button
type="button"
class="btn btn-sm btn-light px-3"
data-bs-dismiss="modal"
style="height:36px; border-radius:8px;"
>

Cancelar

</button>

<button
type="submit"
name="editar_publicacion"
class="btn btn-sm btn-primary px-3"
style="height:36px; border-radius:8px;"
>

Actualizar

</button>

</div>

</form>

</div>

</div>

</div>
<?php } ?>

<!-- MODAL NUEVA PUBLICACIÓN -->

<div
class="modal fade"
id="modalPublicacion"
tabindex="-1"
>

<div class="modal-dialog modal-lg modal-dialog-centered">

<div class="modal-content border-0 rounded-3 overflow-hidden">

<div class="modal-header py-2 px-3">

<h5 class="modal-title fw-bold" style="font-size:15px;">

Nueva Publicación

</h5>

<button
class="btn-close"
data-bs-dismiss="modal"
></button>

</div>

<form
method="POST"
enctype="multipart/form-data"
>

<div class="modal-body">

<div class="row g-2">

<div class="col-lg-6">

<label class="form-label">

Subcategoría

</label>

<select
name="subcategoria_id"
class="form-select"
required
>

<option value="">
Seleccione
</option>

<?php

mysqli_data_seek($resultSubcategorias,0);

while($sub = mysqli_fetch_assoc($resultSubcategorias)){

?>

<option value="<?php echo $sub['id']; ?>">

<?php echo $sub['categoria']; ?>

→

<?php echo $sub['nombre']; ?>

</option>

<?php } ?>

</select>

</div>

<div class="col-lg-6">

<label class="form-label">

Tipo publicación

</label>

<select
name="tipo_publicacion"
id="tipoPublicacion"
class="form-select"
required
>

<option value="">
Seleccione
</option>

<option value="herramientas">
Herramientas
</option>

<option value="actividades">
Actividades
</option>

<option value="eventos">
Eventos
</option>

<option value="galeria">
Galería
</option>

<option value="archivos">
Archivo
</option>

<option value="tutoriales">
Tutorial
</option>

<option value="alertas">
Alerta
</option>

<option value="informativo">
Informativo
</option>

<option value="reuniones">
Reunión
</option>

</select>

</div>

<div class="col-12">

<label class="form-label">

Título

</label>

<input
type="text"
name="titulo"
class="form-control"
required
>

</div>

<div class="col-12">

<label class="form-label">

Descripción

</label>

<textarea
name="descripcion"
class="form-control"
rows="3"
></textarea>

</div>

<div class="col-12">

<label class="form-label">

Portada / Banner (Opcional)

</label>

<input
type="file"
name="imagen"
class="form-control"
>

</div>

<div
id="dynamicFields"
class="row g-2 m-0 p-0 w-100"
></div>

</div>

</div>

<div class="modal-footer py-2 px-3">

<button
type="button"
class="btn btn-sm btn-light px-3"
data-bs-dismiss="modal"
style="height:36px; border-radius:8px;"
>

Cancelar

</button>

<button
type="submit"
name="guardar"
class="btn btn-sm btn-primary px-3"
style="height:36px; border-radius:8px;"
>

Guardar

</button>

</div>

</form>

</div>

</div>

</div>

<!-- SCRIPT DINÁMICO DE CREACIÓN -->

<script>

const tipoPublicacion =
document.getElementById('tipoPublicacion');

const dynamicFields =
document.getElementById('dynamicFields');

tipoPublicacion.addEventListener('change',()=>{

const tipo = tipoPublicacion.value;

dynamicFields.innerHTML='';

if(tipo=='herramientas'){

dynamicFields.innerHTML=`

<div class="col-lg-12 p-0 mt-2">

<label class="form-label">

Link

</label>

<input
type="text"
name="link"
class="form-control"
>

</div>

`;

}

if(tipo=='actividades'){

dynamicFields.innerHTML=`

<div class="col-lg-4 ps-0 pe-1 mt-2">

<label class="form-label">

Fecha inicio

</label>

<input
type="date"
name="fecha_inicio"
class="form-control"
>

</div>

<div class="col-lg-4 px-1 mt-2">

<label class="form-label">

Fecha fin

</label>

<input
type="date"
name="fecha_fin"
class="form-control"
>

</div>

<div class="col-lg-4 pe-0 ps-1 mt-2">

<label class="form-label">

Hora

</label>

<input
type="time"
name="hora"
class="form-control"
>

</div>

`;

}

if(tipo=='eventos'){

dynamicFields.innerHTML=`

<div class="col-lg-4 ps-0 pe-1 mt-2">

<label class="form-label">

Fecha inicio

</label>

<input
type="date"
name="fecha_inicio"
class="form-control"
required
>

</div>

<div class="col-lg-4 px-1 mt-2">

<label class="form-label">

Hora

</label>

<input
type="time"
name="hora"
class="form-control"
required
>

</div>

<div class="col-lg-4 pe-0 ps-1 mt-2">

<label class="form-label">

Fecha final

</label>

<input
type="date"
name="fecha_fin"
class="form-control"
required
>

</div>

`;

}

if(tipo=='archivos'){

dynamicFields.innerHTML=`

<div class="col-12 p-0 mt-2">

<label class="form-label">

Archivo

</label>

<input
type="file"
name="archivo"
class="form-control"
>

</div>

`;

}

if(tipo=='tutoriales'){

dynamicFields.innerHTML=`

<div class="col-lg-6 ps-0 pe-1 mt-2">

<label class="form-label">

Video YouTube / Drive

</label>

<input
type="text"
name="video"
class="form-control"
placeholder="Enlace de video"
>

</div>

<div class="col-lg-6 pe-0 ps-1 mt-2">

<label class="form-label">

PDF

</label>

<input
type="file"
name="archivo"
class="form-control"
>

</div>

`;

}

if(tipo=='alertas'){

dynamicFields.innerHTML=`

<div class="col-lg-6 p-0 mt-2">

<label class="form-label">

Prioridad

</label>

<select
name="prioridad"
class="form-select"
>

<option>Alta</option>
<option>Media</option>
<option>Baja</option>

</select>

</div>

`;

}

if(tipo=='reuniones'){

dynamicFields.innerHTML=`

<div class="col-lg-6 ps-0 pe-1 mt-2">

<label class="form-label">

Fecha

</label>

<input
type="date"
name="fecha_inicio"
class="form-control"
>

</div>

<div class="col-lg-6 pe-0 ps-1 mt-2">

<label class="form-label">

Hora

</label>

<input
type="time"
name="hora"
class="form-control"
>

</div>

`;

}

});

</script>

<!-- =========================================================
SISTEMA DE FILTRADO Y BUSCADOR SIMULTÁNEO EN TIEMPO REAL
========================================================= */ -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const txtBuscar = document.getElementById('buscarPublicacion');
    const selectTipo = document.getElementById('filtroTipoRealTime');
    const tabla = document.getElementById('tablaPublicaciones');

    if (!txtBuscar || !selectTipo || !tabla) return;

    const filas = tabla.querySelectorAll('tbody tr');

    function ejecutarFiltros() {
        const textoBusqueda = txtBuscar.value.toLowerCase().trim();
        const tipoSeleccionado = selectTipo.value.toLowerCase().trim();

        filas.forEach(fila => {
            const tipoFila = fila.getAttribute('data-tipo') || '';
            const coincideTipo = (tipoSeleccionado === 'todos' || tipoFila === tipoSeleccionado);

            const textoFila = fila.textContent.toLowerCase();
            const coincideTexto = textoFila.includes(textoBusqueda);

            if (coincideTipo && coincideTexto) {
                fila.style.setProperty('display', '', 'important');
            } else {
                fila.style.setProperty('display', 'none', 'important');
            }
        });
    }

    txtBuscar.addEventListener('input', ejecutarFiltros);
    selectTipo.addEventListener('change', ejecutarFiltros);
});
</script>

<script>

function eliminarPublicacion(url){

Swal.fire({

title: '¿Eliminar?',
icon: 'warning',
showCancelButton: true,
confirmButtonText: 'Sí',
cancelButtonText: 'No',
confirmButtonColor: '#dc3545'

}).then((result)=>{

if(result.isConfirmed){

window.location = url;

}

});

}

</script>
<script>

function iniciarCountdown(){

const countdowns =
document.querySelectorAll('.countdown-box');

countdowns.forEach(box=>{

const fecha =
new Date(
box.dataset.fecha
).getTime();

function actualizar(){

const ahora =
new Date().getTime();

const distancia =
fecha - ahora;

if(distancia < 0){

box.innerHTML = `

<div class="alert alert-danger py-1 px-2 rounded-2 small m-0">

Evento finalizado

</div>

`;

return;

}

const dias =
Math.floor(
distancia / (1000*60*60*24)
);

const horas =
Math.floor(
(distancia % (1000*60*60*24))
/
(1000*60*60)
);

const minutos =
Math.floor(
(distancia % (1000*60))
/
(1000*60)
);

const segundos =
Math.floor(
(distancia % (1000*60))
/
1000
);

box.innerHTML = `

<div class="bg-primary text-white rounded-3 p-2 text-center">

<div class="d-flex justify-content-center gap-3 small fw-bold">

<div>${dias}d</div>
<div>${horas}h</div>
<div>${minutos}m</div>
<div>${segundos}s</div>

</div>

</div>

`;

}

actualizar();

setInterval(actualizar,1000);

});

}

document.addEventListener(
'DOMContentLoaded',
iniciarCountdown
);

</script>
<style>

/* =========================================
ESTILOS COMPACTOS MINIMALISTAS
========================================= */

/* Tabla Reducida */
.dynamic-table-compact {
    width: 100%;
    border-collapse: collapse;
}

.dynamic-table-compact th {
    padding: 8px 12px !important;
    font-size: 13px !important;
    background-color: #f8fafc;
    border-bottom: 2px solid #e2e8f0;
}

.dynamic-table-compact td {
    padding: 6px 12px !important;
    font-size: 13px !important;
    vertical-align: middle !important;
    border-bottom: 1px solid #edf2f7;
}

/* Botones pequeños de acción */
.btn-compact-action {
    height: 26px !important;
    padding: 0 10px !important;
    font-size: 11px !important;
    border-radius: 4px !important;
    font-weight: 500 !important;
    display: inline-flex;
    align-items: center;
}

/* Reducción General de Contenedor de Modales */
.custom-modal-publicacion{
    max-width: 680px;
}

.custom-modal-publicacion .modal-content{
    border: none;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,.1);
}

.custom-modal-publicacion .modal-body{
    padding: 16px !important;
    max-height: 75vh;
    overflow-y: auto;
}

/* Inputs reducidos dentro del Modal */
.custom-modal-publicacion .form-control,
.custom-modal-publicacion .form-select{
    height: 38px !important;
    border-radius: 6px !important;
    border: 1px solid #cbd5e1;
    font-size: 13px !important;
    padding: 4px 10px !important;
}

.custom-modal-publicacion textarea.form-control{
    height: auto !important;
    min-height: 80px !important;
    padding-top: 8px !important;
}

.custom-modal-publicacion .form-label{
    font-weight: 600 !important;
    margin-bottom: 4px !important;
    font-size: 13px !important;
    color: #334155;
}

.custom-modal-publicacion .btn{
    height: 36px !important;
    padding: 0 16px !important;
    border-radius: 6px !important;
    font-size: 13px !important;
}

@media(max-width:768px){
    .custom-modal-publicacion{
        margin: 8px;
        max-width: 100%;
    }
    .custom-modal-publicacion .modal-body{
        padding: 12px !important;
    }
    .custom-modal-publicacion .modal-footer{
        padding: 12px !important;
        flex-direction: row !important;
    }
    .custom-modal-publicacion .btn{
        width: auto !important;
    }
}

.modal{ z-index: 99999; }
.modal-backdrop{ z-index: 99998; }

</style>
<?php include '../includes/footer.php'; ?>

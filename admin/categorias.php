<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

include '../includes/auth.php';

require_once '../config/conexion.php';

$basePath = '../';

/* =========================================================
GUARDAR CATEGORIA
========================================================= */

if(isset($_POST['guardar_categoria'])){

$nombre = mysqli_real_escape_string(
$conn,
$_POST['nombre_categoria']
);

mysqli_query(
$conn,
"

INSERT INTO categorias
(

nombre,
estado

)

VALUES
(

'$nombre',
'Activo'

)

"
);

header('Location: categorias.php?success=1');
exit;

}

/* =========================================================
GUARDAR SUBCATEGORIA
========================================================= */

if(isset($_POST['guardar_subcategoria'])){

$categoria_id = intval(
$_POST['categoria_id']
);

$nombre = mysqli_real_escape_string(
$conn,
$_POST['nombre_subcategoria']
);

mysqli_query(
$conn,
"

INSERT INTO subcategorias
(

categoria_id,
nombre,
estado

)

VALUES
(

'$categoria_id',
'$nombre',
'Activo'

)

"
);

header('Location: categorias.php?success=1');
exit;

}

/* =========================================================
EDITAR CATEGORIA
========================================================= */

if(isset($_POST['editar_categoria'])){

$id = intval(
$_POST['id_categoria']
);

$nombre = mysqli_real_escape_string(
$conn,
$_POST['nombre_categoria']
);

$estado = mysqli_real_escape_string(
$conn,
$_POST['estado']
);

mysqli_query(
$conn,
"

UPDATE categorias

SET

nombre='$nombre',
estado='$estado'

WHERE id='$id'

"

);

header('Location: categorias.php?update=1');
exit;

}

/* =========================================================
EDITAR SUBCATEGORÍA
========================================================= */

if(isset($_POST['editar_subcategoria'])){

$id = intval(
$_POST['id_subcategoria']
);

$categoria_id = intval(
$_POST['categoria_id']
);

$nombre = mysqli_real_escape_string(
$conn,
$_POST['nombre_subcategoria']
);

$queryUpdate = "

UPDATE subcategorias

SET

categoria_id='$categoria_id',
nombre='$nombre'

WHERE id='$id'

";

$actualizar = mysqli_query(
$conn,
$queryUpdate
);

if(!$actualizar){

die(mysqli_error($conn));

}

header(
'Location: categorias.php?update=1'
);

exit;

}

/* =========================================================
DESACTIVAR CATEGORIA
========================================================= */

if(isset($_GET['desactivar'])){

$id = intval($_GET['desactivar']);

mysqli_query(
$conn,
"

UPDATE categorias

SET estado='Inactivo'

WHERE id='$id'

"
);

header('Location: categorias.php');
exit;

}

/* =========================================================
ACTIVAR CATEGORIA
========================================================= */

if(isset($_GET['activar'])){

$id = intval($_GET['activar']);

mysqli_query(
$conn,
"

UPDATE categorias

SET estado='Activo'

WHERE id='$id'

"
);

header('Location: categorias.php');
exit;

}

/* =========================================================
DESACTIVAR SUBCATEGORIA
========================================================= */

if(isset($_GET['desactivar_sub'])){

$id = intval($_GET['desactivar_sub']);

mysqli_query(
$conn,
"

UPDATE subcategorias

SET estado='Inactivo'

WHERE id='$id'

"
);

header('Location: categorias.php');
exit;

}

/* =========================================================
ACTIVAR SUBCATEGORIA
========================================================= */

if(isset($_GET['activar_sub'])){

$id = intval($_GET['activar_sub']);

mysqli_query(
$conn,
"

UPDATE subcategorias

SET estado='Activo'

WHERE id='$id'

"
);

header('Location: categorias.php');
exit;

}
/* =========================================================
ELIMINAR CATEGORIA
========================================================= */

if(isset($_GET['eliminar'])){

$id = intval($_GET['eliminar']);

mysqli_query(
$conn,
"

DELETE FROM categorias

WHERE id='$id'

"
);

header('Location: categorias.php');
exit;

}

/* =========================================================
ELIMINAR SUBCATEGORIA
========================================================= */

if(isset($_GET['eliminar_sub'])){

$id = intval($_GET['eliminar_sub']);

mysqli_query(
$conn,
"

DELETE FROM subcategorias

WHERE id='$id'

"
);

header('Location: categorias.php');
exit;

}

/* =========================================================
LISTAR CATEGORIAS
========================================================= */

$queryCategorias = "

SELECT *

FROM categorias

WHERE estado='Activo'

ORDER BY nombre ASC

";

$resultCategorias = mysqli_query(
$conn,
$queryCategorias
);

/* =========================================================
LISTAR SUBCATEGORIAS
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

$resultSubcategorias = mysqli_query(
$conn,
$querySubcategorias
);

/* =========================================================
INACTIVAS
========================================================= */

$resultInactivas = mysqli_query(
$conn,
"

SELECT *

FROM categorias

WHERE estado='Inactivo'

ORDER BY nombre ASC

"
);

$resultSubcategoriasInactivas = mysqli_query(
$conn,
"

SELECT subcategorias.*,

categorias.nombre AS categoria

FROM subcategorias

LEFT JOIN categorias
ON subcategorias.categoria_id = categorias.id

WHERE subcategorias.estado='Inactivo'

ORDER BY subcategorias.nombre ASC

"
);

/* =========================================================
HEADER
========================================================= */

include '../includes/header.php';

include '../includes/navbar.php';

?>

<div class="main-container">

<!-- =========================================================
HEADER
========================================================= -->

<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-5">

<div>

<h1 class="page-title">

Categorías y Subcategorías

</h1>

<p class="page-subtitle">

Administración institucional

</p>

</div>

<div>

<button
class="btn-custom btn-info-custom"
data-bs-toggle="modal"
data-bs-target="#modalInactivos"
>

<i class="bi bi-archive-fill"></i>

Inactivos

</button>

</div>

</div>

<!-- =========================================================
GRID
========================================================= -->

<div class="row g-4">

<!-- =========================================================
CATEGORIAS
========================================================= -->

<div class="col-xl-6">

<div class="card-custom">

<div class="d-flex justify-content-between align-items-center mb-4">

<h4 class="fw-bold mb-0">

Categorías

</h4>

<button
class="btn-custom btn-primary-custom"
data-bs-toggle="modal"
data-bs-target="#modalCategoria"
>

<i class="bi bi-plus-circle-fill"></i>

Agregar

</button>

</div>
<div class="table-responsive">

<table class="table-custom">

<thead>

<tr>

<th>Nombre</th>
<th>Estado</th>
<th>Acciones</th>

</tr>

</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($resultCategorias)){ ?>

<tr>

<td>

<?php echo $row['nombre']; ?>

</td>

<td>

<span class="badge bg-success">

<?php echo $row['estado']; ?>

</span>

</td>

<td>

<div class="d-flex gap-2 flex-wrap">

<button
class="btn-custom btn-warning-custom"
data-bs-toggle="modal"
data-bs-target="#editarCategoria<?php echo $row['id']; ?>"
>

Editar

</button>

<button
onclick="window.location='categorias.php?desactivar=<?php echo $row['id']; ?>'"
class="btn-custom btn-info-custom"
>

Desactivar

</button>

</div>

</td>

</tr>

<!-- MODAL EDITAR CATEGORIA -->

<div
class="modal fade"
id="editarCategoria<?php echo $row['id']; ?>"
tabindex="-1"
>

<div class="modal-dialog modal-lg modal-dialog-centered">

<div class="modal-content">

<div class="modal-header">

<h5 class="modal-title">

Editar Categoría

</h5>

<button
class="btn-close"
data-bs-dismiss="modal"
></button>

</div>

<form method="POST">

<div class="modal-body">

<input
type="hidden"
name="id_categoria"
value="<?php echo $row['id']; ?>"
>

<div class="mb-3">

<label class="form-label">
Nombre
</label>

<input
type="text"
name="nombre_categoria"
class="form-control"
value="<?php echo $row['nombre']; ?>"
required
>

</div>

<div class="mb-3">

<label class="form-label">
Estado
</label>

<select
name="estado"
class="form-select"
>

<option
value="Activo"
<?php if($row['estado']=='Activo'){ echo 'selected'; } ?>
>

Activo

</option>

<option
value="Inactivo"
<?php if($row['estado']=='Inactivo'){ echo 'selected'; } ?>
>

Inactivo

</option>

</select>

</div>

</div>

<div class="modal-footer">

<button
type="submit"
name="editar_categoria"
class="btn btn-primary"
>

Actualizar

</button>

</div>

</form>

</div>

</div>

</div>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

<!-- =========================================================
SUBCATEGORIAS
========================================================= -->

<div class="col-xl-6">

<div class="card-custom">

<div class="d-flex justify-content-between align-items-center mb-4">

<h4 class="fw-bold mb-0">

Subcategorías

</h4>

<button
class="btn-custom btn-warning-custom"
data-bs-toggle="modal"
data-bs-target="#modalSubcategoria"
>

<i class="bi bi-plus-circle-fill"></i>

Agregar

</button>

</div>

<div class="table-responsive">

<table class="table-custom">

<thead>

<tr>

<th>Categoría</th>
<th>Subcategoría</th>
<th>Estado</th>
<th>Acciones</th>

</tr>

</thead>

<tbody>

<?php while($sub = mysqli_fetch_assoc($resultSubcategorias)){ ?>

<tr>

<td>

<?php echo $sub['categoria']; ?>

</td>

<td>

<?php echo $sub['nombre']; ?>

</td>

<td>

<span class="badge bg-success">

<?php echo $sub['estado']; ?>

</span>

</td>

<td>

<div class="d-flex gap-2 flex-wrap">

<button
class="btn-custom btn-warning-custom"
data-bs-toggle="modal"
data-bs-target="#editarSubcategoria<?php echo $sub['id']; ?>"
>

Editar

</button>

<button
onclick="window.location='categorias.php?desactivar_sub=<?php echo $sub['id']; ?>'"
class="btn-custom btn-info-custom"
>

Desactivar

</button>

</div>

</td>

</tr>

<!-- MODAL EDITAR SUB -->

<div
class="modal fade"
id="editarSubcategoria<?php echo $sub['id']; ?>"
tabindex="-1"
>

<div class="modal-dialog modal-lg modal-dialog-centered">

<div class="modal-content">

<div class="modal-header">

<h5 class="modal-title">

Editar Subcategoría

</h5>

<button
class="btn-close"
data-bs-dismiss="modal"
></button>

</div>

<form method="POST">

<div class="modal-body">

<input
type="hidden"
name="id_subcategoria"
value="<?php echo $sub['id']; ?>"
>

<div class="mb-3">

<label class="form-label">

Categoría

</label>

<select
name="categoria_id"
class="form-select"
required
>

<?php

$queryCategoriasSelect = mysqli_query(
$conn,
"

SELECT *

FROM categorias

WHERE estado='Activo'

ORDER BY nombre ASC

"

);

while($cat = mysqli_fetch_assoc($queryCategoriasSelect)){

?>

<option
value="<?php echo $cat['id']; ?>"

<?php
if($sub['categoria_id']==$cat['id']){
echo 'selected';
}
?>

>

<?php echo $cat['nombre']; ?>

</option>

<?php } ?>

</select>

</div>

<div class="mb-3">

<label class="form-label">

Subcategoría

</label>

<input
type="text"
name="nombre_subcategoria"
class="form-control"
value="<?php echo $sub['nombre']; ?>"
required
>

</div>

</div>

<div class="modal-footer">

<button
type="submit"
name="editar_subcategoria"
class="btn btn-primary"
>

Actualizar

</button>

</div>

</form>

</div>

</div>

</div>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

<!-- MODAL NUEVA CATEGORIA -->

<div
class="modal fade"
id="modalCategoria"
tabindex="-1"
>

<div class="modal-dialog modal-lg modal-dialog-centered">

<div class="modal-content">

<div class="modal-header">

<h5 class="modal-title">

Nueva Categoría

</h5>

<button
class="btn-close"
data-bs-dismiss="modal"
></button>

</div>

<form method="POST">

<div class="modal-body">

<div class="mb-3">

<label class="form-label">

Nombre categoría

</label>

<input
type="text"
name="nombre_categoria"
class="form-control"
required
>

</div>

</div>

<div class="modal-footer">

<button
type="submit"
name="guardar_categoria"
class="btn btn-primary"
>

Guardar

</button>

</div>

</form>

</div>

</div>

</div>

<!-- MODAL NUEVA SUB -->

<div
class="modal fade"
id="modalSubcategoria"
tabindex="-1"
>

<div class="modal-dialog modal-lg modal-dialog-centered">

<div class="modal-content">

<div class="modal-header">

<h5 class="modal-title">

Nueva Subcategoría

</h5>

<button
class="btn-close"
data-bs-dismiss="modal"
></button>

</div>

<form method="POST">

<div class="modal-body">

<div class="mb-3">

<label class="form-label">

Categoría

</label>

<select
name="categoria_id"
class="form-select"
required
>

<?php

mysqli_data_seek($resultCategorias,0);

while($cat = mysqli_fetch_assoc($resultCategorias)){

?>

<option
value="<?php echo $cat['id']; ?>"
>

<?php echo $cat['nombre']; ?>

</option>

<?php } ?>

</select>

</div>

<div class="mb-3">

<label class="form-label">

Subcategoría

</label>

<input
type="text"
name="nombre_subcategoria"
class="form-control"
required
>

</div>

</div>

<div class="modal-footer">

<button
type="submit"
name="guardar_subcategoria"
class="btn btn-primary"
>

Guardar

</button>

</div>

</form>

</div>

</div>

</div>

<!-- MODAL INACTIVOS -->

<div
class="modal fade"
id="modalInactivos"
tabindex="-1"
>

<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-inactivos-custom">

<div class="modal-content">

<div class="modal-header">

<h5 class="modal-title">

Elementos Inactivos

</h5>

<button
class="btn-close"
data-bs-dismiss="modal"
></button>

</div>

<div class="modal-body">

<div class="row g-4">

<!-- CATEGORIAS -->

<div class="col-lg-6">

<div class="card-custom">

<h5 class="fw-bold mb-4">

Categorías Inactivas

</h5>

<?php while($inactive = mysqli_fetch_assoc($resultInactivas)){ ?>

<div class="d-flex justify-content-between align-items-center inactive-item">

<div>

<?php echo $inactive['nombre']; ?>

</div>

<div class="d-flex gap-2">

<!-- ACTIVAR -->

<button
onclick="window.location='categorias.php?activar=<?php echo $inactive['id']; ?>'"
class="btn btn-success btn-sm"
>

Activar

</button>

<!-- ELIMINAR -->

<button
onclick="eliminarCategoria(
'categorias.php?eliminar=<?php echo $inactive['id']; ?>'
)"
class="btn btn-danger btn-sm"
>

Eliminar

</button>

</div>

</div>

<?php } ?>

</div>

</div>

<!-- SUBCATEGORIAS -->

<div class="col-lg-6">

<div class="card-custom">

<h5 class="fw-bold mb-4">

Subcategorías Inactivas

</h5>

<?php while($subInactive = mysqli_fetch_assoc($resultSubcategoriasInactivas)){ ?>

<div class="d-flex justify-content-between align-items-center border-bottom py-3">

<div>

<?php echo $subInactive['nombre']; ?>

</div>

<div class="d-flex gap-2">

<!-- ACTIVAR -->

<button
onclick="window.location='categorias.php?activar_sub=<?php echo $subInactive['id']; ?>'"
class="btn btn-success btn-sm"
>

Activar

</button>

<!-- ELIMINAR -->

<button
onclick="eliminarSubcategoria(
'categorias.php?eliminar_sub=<?php echo $subInactive['id']; ?>'
)"
class="btn btn-danger btn-sm"
>

Eliminar

</button>

</div>

</div>

<?php } ?>

</div>

</div>

</div>

</div>

</div>

</div>

</div>
<script>

function eliminarCategoria(url){

Swal.fire({

title:'¿Eliminar categoría?',
text:'Esta acción no se puede deshacer.',
icon:'warning',
showCancelButton:true,
confirmButtonText:'Sí, eliminar',
cancelButtonText:'Cancelar',
confirmButtonColor:'#dc3545'

}).then((result)=>{

if(result.isConfirmed){

window.location = url;

}

});

}

function eliminarSubcategoria(url){

Swal.fire({

title:'¿Eliminar subcategoría?',
text:'Esta acción no se puede deshacer.',
icon:'warning',
showCancelButton:true,
confirmButtonText:'Sí, eliminar',
cancelButtonText:'Cancelar',
confirmButtonColor:'#dc3545'

}).then((result)=>{

if(result.isConfirmed){

window.location = url;

}

});

}

</script>
</div>
<style>

/* =========================================
MODAL INACTIVOS
========================================= */

.modal-inactivos-custom{

max-width:1000px;

}

/* CONTENT */

.modal-inactivos-custom .modal-content{

border:none;

border-radius:28px;

overflow:hidden;

box-shadow:0 25px 60px rgba(0,0,0,.18);

}

/* HEADER */

.modal-inactivos-custom .modal-header{

padding:22px 28px;

border-bottom:1px solid #eef2f7;

}

/* BODY */

.modal-inactivos-custom .modal-body{

padding:28px;

max-height:75vh;

overflow-y:auto;

}

/* CARD */

.modal-inactivos-custom .card-custom{

height:100%;

border-radius:24px;

}

/* ITEMS */

.modal-inactivos-custom .inactive-item{

padding:16px 0;

border-bottom:1px solid #eef2f7;

}

/* BOTONES */

.modal-inactivos-custom .btn{

border-radius:12px;

font-weight:600;

}

/* MOBILE */

@media(max-width:768px){

.modal-inactivos-custom{

margin:10px;

max-width:100%;

}

.modal-inactivos-custom .modal-content{

border-radius:24px;

}

.modal-inactivos-custom .modal-header{

padding:18px;

}

.modal-inactivos-custom .modal-body{

padding:18px;

max-height:70vh;

overflow-y:auto;

}

}

/* FIX NAVBAR */

.modal{

z-index:99999;

}

.modal-backdrop{

z-index:99998;

}
/* =========================================
SWEET ALERT SOBRE MODALES
========================================= */

.swal2-container{

z-index:999999 !important;

}

.swal2-popup{

z-index:999999 !important;

}
</style>
<?php include '../includes/footer.php'; ?>
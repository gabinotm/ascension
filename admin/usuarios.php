<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

require_once '../config/conexion.php';

/* =========================================================
ROLES
========================================================= */

$queryRoles = "

SELECT *

FROM roles

ORDER BY nombre ASC

";

$resultRoles = mysqli_query(
$conn,
$queryRoles
);

/* =========================================================
GUARDAR
========================================================= */

if(isset($_POST['guardar'])){

$nombres = mysqli_real_escape_string(
$conn,
$_POST['nombres']
);

$usuario = mysqli_real_escape_string(
$conn,
$_POST['usuario']
);

$password = password_hash(
$_POST['password'],
PASSWORD_DEFAULT
);

$rol = $_POST['rol_id'];

/* VALIDAR */

$validar = mysqli_query(
$conn,
"
SELECT *
FROM usuarios
WHERE usuario='$usuario'
"
);

if(mysqli_num_rows($validar)>0){

header('Location: usuarios.php?error=1');
exit;

}

/* INSERT */

mysqli_query(
$conn,
"
INSERT INTO usuarios
(
nombres,
usuario,
password,
rol_id
)

VALUES
(
'$nombres',
'$usuario',
'$password',
'$rol'
)
"
);

header('Location: usuarios.php?success=1');
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
DELETE FROM usuarios
WHERE id='$id'
"
);

header('Location: usuarios.php?delete=1');
exit;

}

/* =========================================================
ACTUALIZAR
========================================================= */

if(isset($_POST['actualizar'])){

$id = $_POST['id'];

$nombres = mysqli_real_escape_string(
$conn,
$_POST['nombres']
);

$usuario = mysqli_real_escape_string(
$conn,
$_POST['usuario']
);

$rol = $_POST['rol_id'];

if($_POST['password']!=''){

$password = password_hash(
$_POST['password'],
PASSWORD_DEFAULT
);

$query = "

UPDATE usuarios

SET

nombres='$nombres',
usuario='$usuario',
password='$password',
rol_id='$rol'

WHERE id='$id'

";

}else{

$query = "

UPDATE usuarios

SET

nombres='$nombres',
usuario='$usuario',
rol_id='$rol'

WHERE id='$id'

";

}

mysqli_query($conn,$query);

header('Location: usuarios.php?update=1');
exit;

}

/* =========================================================
LISTAR
========================================================= */

$queryUsuarios = "

SELECT usuarios.*,

roles.nombre AS rol

FROM usuarios

LEFT JOIN roles
ON usuarios.rol_id = roles.id

ORDER BY usuarios.id DESC

";

$resultUsuarios = mysqli_query(
$conn,
$queryUsuarios
);

/* =========================================================
HEADER
========================================================= */

include '../includes/header.php';
include '../includes/navbar.php';

?>

<div class="main-container">

<!-- HEADER -->

<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-5">

<div>

<h1 class="page-title">

Usuarios

</h1>

<p class="page-subtitle">

Administración de usuarios y roles

</p>

</div>

<button
class="btn-custom btn-primary-custom"
data-bs-toggle="modal"
data-bs-target="#modalUsuario"
>

+ Nuevo Usuario

</button>

</div>

<!-- FILTROS -->

<div class="card-custom mb-4">

<div class="row g-3">

<div class="col-lg-8">

<input
type="text"
id="searchInput"
class="form-control"
placeholder="Buscar usuario..."
>

</div>

<div class="col-lg-4">

<select
id="filterRol"
class="form-select"
>

<option value="">
Todos los roles
</option>

<?php

mysqli_data_seek($resultRoles,0);

while($rol = mysqli_fetch_assoc($resultRoles)){

?>

<option value="<?php echo $rol['nombre']; ?>">

<?php echo $rol['nombre']; ?>

</option>

<?php } ?>

</select>

</div>

</div>

</div>

<!-- TABLA -->

<div class="card-custom">

<div class="table-responsive">

<table class="table-custom">

<thead>

<tr>

<th>ID</th>
<th>Nombres</th>
<th>Usuario</th>
<th>Rol</th>
<th>Acciones</th>

</tr>

</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($resultUsuarios)){ ?>

<tr>

<td>

<?php echo $row['id']; ?>

</td>

<td>

<?php echo $row['nombres']; ?>

</td>

<td>

<?php echo $row['usuario']; ?>

</td>

<td>

<span class="badge bg-primary rounded-pill px-3 py-2">

<?php echo $row['rol']; ?>

</span>

</td>

<td>

<div class="d-flex flex-wrap gap-2">

<button
class="btn-custom btn-warning-custom"
onclick='editUser(
<?php echo json_encode($row); ?>
)'
>

Editar

</button>

<button
class="btn-custom btn-info-custom"
onclick="deleteUser(<?php echo $row['id']; ?>)"
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

<!-- MODAL -->

<div
class="modal fade"
id="modalUsuario"
tabindex="-1"
>

<div class="modal-dialog modal-lg modal-dialog-centered">

<div class="modal-content border-0 rounded-4">

<div class="modal-header">

<h5
class="modal-title fw-bold"
id="modalTitle"
>

Nuevo Usuario

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
name="id"
id="id"
>

<div class="row g-4">

<div class="col-lg-6">

<label class="form-label">

Nombres

</label>

<input
type="text"
name="nombres"
id="nombres"
class="form-control"
required
>

</div>

<div class="col-lg-6">

<label class="form-label">

Usuario

</label>

<input
type="text"
name="usuario"
id="usuario"
class="form-control"
required
>

</div>

<div class="col-lg-6">

<label class="form-label">

Contraseña

</label>

<input
type="password"
name="password"
id="password"
class="form-control"
>

</div>

<div class="col-lg-6">

<label class="form-label">

Rol

</label>

<select
name="rol_id"
id="rol"
class="form-select"
required
>

<option value="">
Seleccione
</option>

<?php

mysqli_data_seek($resultRoles,0);

while($r = mysqli_fetch_assoc($resultRoles)){

?>

<option value="<?php echo $r['id']; ?>">

<?php echo $r['nombre']; ?>

</option>

<?php } ?>

</select>

</div>

</div>

</div>

<div class="modal-footer">

<button
type="button"
class="btn btn-light rounded-pill px-4"
data-bs-dismiss="modal"
>

Cancelar

</button>

<button
type="submit"
name="guardar"
id="submitBtn"
class="btn btn-primary rounded-pill px-4"
>

Guardar

</button>

</div>

</form>

</div>

</div>

</div>

<script>

/* =========================================================
EDITAR
========================================================= */

function editUser(data){

const modal = new bootstrap.Modal(
document.getElementById('modalUsuario')
);

modal.show();

document.getElementById(
'modalTitle'
).innerHTML='Editar Usuario';

document.getElementById(
'id'
).value=data.id;

document.getElementById(
'nombres'
).value=data.nombres;

document.getElementById(
'usuario'
).value=data.usuario;

document.getElementById(
'rol'
).value=data.rol_id;

document.getElementById(
'submitBtn'
).name='actualizar';

}

/* =========================================================
ELIMINAR
========================================================= */

function deleteUser(id){

Swal.fire({

title:'¿Eliminar usuario?',
text:'Esta acción no se puede deshacer.',
icon:'warning',
showCancelButton:true,
confirmButtonText:'Sí, eliminar',
cancelButtonText:'Cancelar',
confirmButtonColor:'#dc3545'

}).then((result)=>{

if(result.isConfirmed){

window.location='usuarios.php?eliminar='+id;

}

});

}

/* =========================================================
FILTROS
========================================================= */

const searchInput =
document.getElementById('searchInput');

const filterRol =
document.getElementById('filterRol');

function filterTable(){

const text =
searchInput.value.toLowerCase();

const rol =
filterRol.value.toLowerCase();

const rows =
document.querySelectorAll('tbody tr');

rows.forEach(row=>{

const contenido =
row.innerText.toLowerCase();

const rolRow =
row.children[3].innerText.toLowerCase();

const matchText =
contenido.includes(text);

const matchRol =
rol=='' || rolRow.includes(rol);

row.style.display =
(matchText && matchRol)
? ''
: 'none';

});

}

searchInput.addEventListener(
'keyup',
filterTable
);

filterRol.addEventListener(
'change',
filterTable
);

/* =========================================================
ALERTAS
========================================================= */

<?php if(isset($_GET['success'])){ ?>

Swal.fire({
icon:'success',
title:'Usuario creado correctamente'
});

<?php } ?>

<?php if(isset($_GET['update'])){ ?>

Swal.fire({
icon:'success',
title:'Usuario actualizado'
});

<?php } ?>

<?php if(isset($_GET['delete'])){ ?>

Swal.fire({
icon:'success',
title:'Usuario eliminado'
});

<?php } ?>

<?php if(isset($_GET['error'])){ ?>

Swal.fire({
icon:'error',
title:'El usuario ya existe'
});

<?php } ?>

</script>

<?php include '../includes/footer.php'; ?>
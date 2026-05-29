<?php

require_once 'config/conexion.php';

/* =========================================================
BUSQUEDA
========================================================= */

$buscar =
$_GET['buscar'] ?? '';

$tipo =
$_GET['tipo'] ?? '';

$subcategoria =
$_GET['subcategoria'] ?? '';

/* =========================================================
QUERY
========================================================= */

$query = "

SELECT publicaciones.*,

usuarios.nombres AS autor

FROM publicaciones

LEFT JOIN usuarios
ON publicaciones.usuario_id = usuarios.id

WHERE publicaciones.subcategoria_id='$subcategoria'

";

/* =========================================================
BUSCADOR
========================================================= */

if($buscar != ''){

$query .= "

AND
(

titulo LIKE '%$buscar%'

OR

descripcion LIKE '%$buscar%'

)

";

}

/* =========================================================
TIPO
========================================================= */

if($tipo != ''){

$query .= "

AND tipo_publicacion='$tipo'

";

}

/* =========================================================
ORDER
========================================================= */

$query .= "

ORDER BY publicaciones.id DESC

";

/* =========================================================
RESULT
========================================================= */

$result = mysqli_query(
$conn,
$query
);

/* =========================================================
SIN RESULTADOS
========================================================= */

if(mysqli_num_rows($result) == 0){

?>

<div class="col-12">

<div class="alert alert-warning rounded-4">

No se encontraron publicaciones.

</div>

</div>

<?php

exit;

}

/* =========================================================
CARDS
========================================================= */

while($row = mysqli_fetch_assoc($result)){

?>

<div class="col-xl-3 col-lg-4 col-md-6">

<div class="card-custom h-100 d-flex flex-column">

<!-- IMAGEN -->

<?php if($row['imagen'] != ''){ ?>

<img
src="img/<?php echo $row['imagen']; ?>"
class="img-fluid rounded-4 mb-4"
style="
height:220px;
object-fit:cover;
width:100%;
"
>

<?php } ?>

<!-- BADGE -->

<div class="mb-3">

<span class="badge bg-primary rounded-pill px-3 py-2">

<?php echo ucfirst($row['tipo_publicacion']); ?>

</span>

</div>

<!-- AUTOR -->

<div class="d-flex flex-wrap gap-3 mb-3 small text-secondary">

<div>

<i class="bi bi-person-fill"></i>

<?php echo $row['autor'] ?? 'Administrador'; ?>

</div>

<div>

<i class="bi bi-clock-fill"></i>

<?php echo date(
'd/m/Y h:i A',
strtotime($row['fecha_registro'])
); ?>

</div>

</div>

<!-- TITULO -->

<h4 class="fw-bold mb-3">

<?php echo $row['titulo']; ?>

</h4>

<!-- DESCRIPCION -->

<p class="text-secondary flex-grow-1">

<?php

echo substr(
strip_tags($row['descripcion']),
0,
120
);

?>...

</p>

<!-- COUNTDOWN -->

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
class="countdown-box mt-3"
data-fecha="<?php echo $row['fecha_inicio'].' '.$row['hora']; ?>"
>

Cargando cuenta regresiva...

</div>

<?php } ?>

<!-- BOTONES -->

<div class="mt-4 d-flex flex-wrap gap-2">

<!-- LINK -->

<?php if($row['link'] != ''){ ?>

<a
href="<?php echo $row['link']; ?>"
target="_blank"
class="btn btn-primary rounded-pill px-4"
>

Abrir

</a>

<?php } ?>

<!-- ARCHIVO -->

<?php if($row['archivo'] != ''){ ?>

<a
href="img/<?php echo $row['archivo']; ?>"
target="_blank"
class="btn btn-outline-primary rounded-pill px-4"
>

Archivo

</a>

<?php } ?>

<!-- VIDEO -->

<?php if($row['video'] != ''){ ?>

<button
class="btn btn-danger rounded-pill px-4"
data-bs-toggle="modal"
data-bs-target="#video<?php echo $row['id']; ?>"
>

Tutorial

</button>

<?php } ?>

</div>

</div>

</div>

<!-- MODAL VIDEO -->

<div
class="modal fade"
id="video<?php echo $row['id']; ?>"
tabindex="-1"
>

<div class="modal-dialog modal-xl modal-dialog-centered">

<div class="modal-content border-0 rounded-4 overflow-hidden">

<div class="modal-header">

<h5 class="modal-title fw-bold">

<?php echo $row['titulo']; ?>

</h5>

<button
class="btn-close"
data-bs-dismiss="modal"
></button>

</div>

<div class="modal-body">

<div class="ratio ratio-16x9">

<iframe
src="https://www.youtube.com/embed/<?php

preg_match(
'/[\\?\\&]v=([^\\?\\&]+)/',
$row['video'],
$matches
);

echo isset($matches[1])
? $matches[1]
: '';

?>"
allowfullscreen
></iframe>

</div>

</div>

</div>

</div>

</div>

<?php } ?>
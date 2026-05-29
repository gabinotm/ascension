<?php

require_once 'config/conexion.php';

$buscar = $_GET['buscar'] ?? '';

$buscar = mysqli_real_escape_string(
$conn,
$buscar
);

$query = "

SELECT *

FROM publicaciones

WHERE
(

titulo LIKE '%$buscar%'
OR descripcion LIKE '%$buscar%'
OR tipo_publicacion LIKE '%$buscar%'

)

ORDER BY id DESC

LIMIT 8

";

$result = mysqli_query(
$conn,
$query
);

while($row = mysqli_fetch_assoc($result)){

?>

<div class="col-xl-3 col-lg-4 col-md-6">

<div class="card-custom h-100 d-flex flex-column">

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

<div class="mb-3">

<span class="badge bg-primary rounded-pill px-3 py-2">
<?php echo ucfirst($row['tipo_publicacion']); ?>
</span>

</div>

<h4 class="fw-bold mb-3">
<?php echo $row['titulo']; ?>
</h4>

<p class="text-secondary flex-grow-1">

<?php

echo substr(
strip_tags($row['descripcion']),
0,
120
);

?>...

</p>

<div class="mt-4 d-flex flex-wrap gap-2">

<a
href="modulo.php?subcategoria=<?php echo $row['subcategoria_id']; ?>"
class="btn btn-primary rounded-pill px-4"
>
Ver más
</a>

</div>

</div>

</div>

<?php } ?>
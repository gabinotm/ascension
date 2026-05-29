<?php

include 'includes/header.php';

include 'config/conexion.php';

$busqueda =
mysqli_real_escape_string(
$conn,
$_GET['q']
);

$query = "

SELECT *

FROM publicaciones

WHERE

titulo LIKE '%$busqueda%'

OR

descripcion LIKE '%$busqueda%'

ORDER BY id DESC

";

$resultado =
mysqli_query($conn,$query);

?>

<div class="container py-5">

<h2 class="mb-5">

Resultados para:

"<?php echo $busqueda; ?>"

</h2>

<div class="row g-4">

<?php while($row=mysqli_fetch_assoc($resultado)){ ?>

<div class="col-lg-4">

<div class="card-publicacion">

<h4>

<?php echo $row['titulo']; ?>

</h4>

<p>

<?php echo $row['descripcion']; ?>

</p>

</div>

</div>

<?php } ?>

</div>

</div>

<?php include 'includes/footer.php'; ?>
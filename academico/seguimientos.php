<?php

include '../includes/header.php';
include '../includes/navbar.php';

require_once '../config/conexion.php';

/* =========================
SUBCATEGORÍA
========================= */

$subcategoria = 1;

/* =========================
CONSULTAR
========================= */

$query = "

SELECT herramientas.*,

categorias.nombre AS categoria,

subcategorias.nombre AS subcategoria

FROM herramientas

LEFT JOIN categorias
ON herramientas.categoria_id = categorias.id

LEFT JOIN subcategorias
ON herramientas.subcategoria_id = subcategorias.id

WHERE herramientas.subcategoria_id = '$subcategoria'

ORDER BY herramientas.id DESC

";

$resultado = mysqli_query($conn,$query);

?>

<!-- TITLE -->

<section class="page-title">

<h2>Seguimientos</h2>

<p>
Herramientas de seguimiento académico
</p>

</section>

<!-- BUSCADOR -->

<?php include '../includes/search.php'; ?>

<!-- CARDS -->

<section class="container">

<?php while($row = mysqli_fetch_assoc($resultado)){ ?>

<div class="card">

<div class="card-image">

<img
src="../assets/img/<?php echo $row['imagen']; ?>"
>

</div>

<div class="card-content">

<h3>

<?php echo $row['titulo']; ?>

</h3>

<p>

<?php echo $row['descripcion']; ?>

</p>

<a
href="<?php echo $row['link']; ?>"
target="_blank"
>

Ingresar

</a>

</div>

</div>

<?php } ?>

</section>

<?php include '../includes/footer.php'; ?>
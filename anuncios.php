<?php

include 'includes/header.php';

$basePath = '';

include 'includes/navbar.php';

/* =========================
CONSULTAR ANUNCIOS
========================= */

$queryAnuncios = "

SELECT anuncios.*,

usuarios.nombres,

roles.nombre AS rol,

categorias.nombre AS categoria

FROM anuncios

LEFT JOIN usuarios
ON anuncios.usuario_id = usuarios.id

LEFT JOIN roles
ON usuarios.rol_id = roles.id

LEFT JOIN categorias
ON anuncios.categoria_id = categorias.id

WHERE anuncios.estado='Publicado'

ORDER BY anuncios.id DESC

";

$resultAnuncios = mysqli_query($conn,$queryAnuncios);

?>

<!-- =========================
PAGE TITLE
========================= -->

<section class="page-title">

<h2>
Anuncios Institucionales
</h2>

<p>
Comunicados y publicaciones importantes
</p>

</section>

<!-- =========================
ANUNCIOS
========================= -->

<section class="announcement-container">

<?php while($row = mysqli_fetch_assoc($resultAnuncios)){ ?>

<div class="announcement-card">

<!-- HEADER -->

<div class="announcement-header">

<div class="announcement-user">

<div class="announcement-avatar">

<i class="fa-solid fa-user"></i>

</div>

<div>

<h4>

<?php echo $row['nombres']; ?>

</h4>

<span>

<?php echo $row['rol']; ?>

</span>

</div>

</div>

<div class="announcement-top-right">

<span class="announcement-category">

<?php echo $row['categoria']; ?>

</span>

<span class="announcement-date">

<?php echo date(
'd/m/Y H:i',
strtotime($row['fecha_publicacion'])
); ?>

</span>

</div>

</div>

<!-- BODY -->

<div class="announcement-body">

<h3>

<?php echo $row['titulo']; ?>

</h3>

<div class="announcement-type">

<?php echo $row['tipo']; ?>

</div>

<p>

<?php echo nl2br($row['descripcion']); ?>

</p>

<?php if($row['imagen'] != ''){ ?>

<img
src="assets/img/anuncios/<?php echo $row['imagen']; ?>"
class="announcement-image"
>

<?php } ?>

</div>

</div>

<?php } ?>

</section>

<?php include 'includes/footer.php'; ?>
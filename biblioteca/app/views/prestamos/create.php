<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="content">

<h1>Nuevo Préstamo</h1>

<form action="?url=prestamos/store" method="POST">

<select name="lector_id">

<?php foreach($lectores as $lector): ?>

<option value="<?= $lector['id'] ?>">

<?= $lector['nombre'] ?>
<?= $lector['apellido'] ?>

</option>

<?php endforeach; ?>

</select>

<br><br>

<select name="libro_id">

<?php foreach($libros as $libro): ?>

<option value="<?= $libro['id'] ?>">

<?= $libro['titulo'] ?>

</option>

<?php endforeach; ?>

</select>

<br><br>

<input
type="date"
name="fecha_prestamo"
required>

<br><br>

<input
type="date"
name="fecha_devolucion"
required>

<br><br>

<select name="estado">

<option value="prestado">
Prestado
</option>

<option value="devuelto">
Devuelto
</option>

</select>

<br><br>

<button class="btn">
Guardar
</button>

</form>

</div>

<?php require '../app/views/layouts/footer.php'; ?>
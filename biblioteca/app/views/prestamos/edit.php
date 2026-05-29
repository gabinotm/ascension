<?php require '../app/views/layouts/header.php'; ?>

<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="content">

<h1>Editar Préstamo</h1>

<form method="POST" action="?url=prestamos/update">

<input
type="hidden"
name="id"
value="<?= $prestamo['id'] ?>">

<p>
<label>Lector</label>

<select name="lector_id">

<?php foreach($lectores as $lector): ?>

<option
value="<?= $lector['id'] ?>"
<?= $prestamo['lector_id'] == $lector['id'] ? 'selected' : '' ?>>

<?= $lector['nombre'] ?>

<?= $lector['apellido'] ?>

</option>

<?php endforeach; ?>

</select>
</p>

<p>
<label>Libro</label>

<select name="libro_id">

<?php foreach($libros as $libro): ?>

<option
value="<?= $libro['id'] ?>"
<?= $prestamo['libro_id'] == $libro['id'] ? 'selected' : '' ?>>

<?= $libro['titulo'] ?>

</option>

<?php endforeach; ?>

</select>
</p>

<p>
<label>Fecha Préstamo</label>

<input
type="date"
name="fecha_prestamo"
value="<?= $prestamo['fecha_prestamo'] ?>">

</p>

<p>
<label>Fecha Devolución</label>

<input
type="date"
name="fecha_devolucion"
value="<?= $prestamo['fecha_devolucion'] ?>">

</p>

<button type="submit">
Actualizar
</button>

</form>

</div>

<?php require '../app/views/layouts/footer.php'; ?>

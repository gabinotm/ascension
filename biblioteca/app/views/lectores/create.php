<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="content">

<h1>Nuevo Lector</h1>

<form action="?url=lectores/store" method="POST">

<p>
    DNI
    <input type="text" name="dni" required>
</p>

<p>
    Nombre
    <input type="text" name="nombre" required>
</p>

<p>
    Apellido
    <input type="text" name="apellido" required>
</p>

<p>
    Correo
    <input type="email" name="correo">
</p>

<p>
    Teléfono
    <input type="text" name="telefono">
</p>

<button type="submit" class="btn">
    Guardar
</button>

</form>

</div>

<?php require '../app/views/layouts/footer.php'; ?>
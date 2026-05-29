<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="content">
    
<!DOCTYPE html>
<html>
<head>

    <meta charset="UTF-8">

    <title>Nuevo Libro</title>

</head>
<body>

<h1>Registrar Libro</h1>

<form action="?url=libros/store" method="POST">

    <p>
        <label>ISBN</label><br>

        <input
            type="text"
            name="isbn"
            required
        >
    </p>

    <p>
        <label>Título</label><br>

        <input
            type="text"
            name="titulo"
            required
        >
    </p>

    <p>
        <label>Autor</label><br>

        <input
            type="text"
            name="autor"
            required
        >
    </p>

    <p>
        <label>Cantidad Total</label><br>

        <input
            type="number"
            name="cantidad_total"
            required
        >
    </p>

    <p>
        <label>Cantidad Disponible</label><br>

        <input
            type="number"
            name="cantidad_disponible"
            required
        >
    </p>

    <button type="submit">

        Guardar Libro

    </button>

</form>

<p>
    <a href="?url=libros">
        Volver
    </a>
</p>

</body>
</html>
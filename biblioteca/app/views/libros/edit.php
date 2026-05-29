<?php 
/** @var array $libro */ // Esto soluciona el subrayado del editor
require '../app/views/layouts/header.php'; 
require '../app/views/layouts/sidebar.php'; 
?>

<div class="content">
    <h1>Editar Libro</h1>

    <form action="?url=libros/update" method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($libro['id'] ?? '') ?>">

        <p>
            ISBN: <input type="text" name="isbn" value="<?= htmlspecialchars($libro['isbn'] ?? '') ?>">
        </p>

        <p>
            Título: <input type="text" name="titulo" value="<?= htmlspecialchars($libro['titulo'] ?? '') ?>">
        </p>

        <p>
            Autor: <input type="text" name="autor" value="<?= htmlspecialchars($libro['autor'] ?? '') ?>">
        </p>

        <p>
            Total: <input type="number" name="cantidad_total" value="<?= htmlspecialchars($libro['cantidad_total'] ?? '') ?>">
        </p>

        <p>
            Disponible: <input type="number" name="cantidad_disponible" value="<?= htmlspecialchars($libro['cantidad_disponible'] ?? '') ?>">
        </p>

        <button type="submit">Actualizar</button>
    </form>
</div>

<?php require '../app/views/layouts/footer.php'; ?>
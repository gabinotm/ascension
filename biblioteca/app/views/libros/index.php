<?php require '../app/views/layouts/header.php'; ?>

<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="content crud-page libros-page">


   <div class="page-header">

    <h1>Libros</h1>

    <div class="acciones-header">

        <a
        href="?url=scanner&destino=libros"
        class="btn btn-success">

        📷 Escanear Libro

        </a>

        <a
        href="?url=libros/create"
        class="btn btn-warning">

        Nuevo Libro

        </a>

    </div>

</div>
    <div class="search-box">
        <input type="text" id="buscar" placeholder="Buscar libro..." class="form-control">

    </div>
    <table class="table">

        <thead>
            <tr>
                <th>ID</th>
                <th>ISBN</th>
                <th>Título</th>
                <th>Autor</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php
                $libros = $libros ?? [];
                ?>
            <?php foreach ($libros as $libro): ?>

            <tr>

                <td><?= $libro['id'] ?></td>
                <td><?= $libro['isbn'] ?></td>
                <td><?= $libro['titulo'] ?></td>
                <td><?= $libro['autor'] ?></td>

                <td>
                    <?= $libro['cantidad_disponible'] ?>
                    /
                    <?= $libro['cantidad_total'] ?>
                </td>
                <div class="acciones">
                    <td>
                        <a href="?url=libros/edit&id=<?= $libro['id'] ?> " class="btn btn-primary">
                            Editar
                        </a>

                        <a href="?url=libros/delete&id=<?= $libro['id'] ?>"
                            onclick="return confirm('¿Eliminar este libro?')" class="btn btn-danger">
                            Eliminar
                        </a>
                    </td>
                    </ </tr>

                    <?php endforeach; ?>

        </tbody>

    </table>
</div>
<script>
const buscar = document.getElementById("buscar");

buscar.addEventListener("keyup", function() {

    let texto = this.value.toLowerCase();

    document.querySelectorAll("tbody tr").forEach(fila => {

        fila.style.display =
            fila.textContent.toLowerCase().includes(texto) ?
            "" :
            "none";

    });

});
</script>
<?php require '../app/views/layouts/footer.php'; ?>
</body>

</html>
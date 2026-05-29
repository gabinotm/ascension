<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="content">


    <!DOCTYPE html>
    <html>

    <head>

        <meta charset="UTF-8">

        <title>Libros</title>

    </head>

    <body>

        <div class="page-header">

    <h1>Libros</h1>

    <a href="?url=lectores/create" class="btn">
        Nuevo Libro
    </a>

</div>
        <input
            type="text"
            id="buscar"
            placeholder="Buscar libro..."
            style="padding:10px; width:300px; margin:15px 0;">
        <table>

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

                        <td>
                            <a href="?url=libros/edit&id=<?= $libro['id'] ?>">
                                Editar
                            </a>

                            <a
                                href="?url=libros/delete&id=<?= $libro['id'] ?>"
                                onclick="return confirm('¿Eliminar este libro?')">
                                Eliminar
                            </a>
                        </td>

                    </tr>

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
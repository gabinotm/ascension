<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="content crud-page lectores-page">

    <div class="page-header">

        <h1>Lectores</h1>

        <a href="?url=lectores/create" class="btn btn-warning">
            Nuevo Lector
        </a>

    </div>
<div class="search-box">

    <input
        type="text"
        id="buscar"
        class="form-control"
        placeholder="Buscar lector...">

</div>
    <table class="table">

        <thead>

            <tr>
                <th>ID</th>
                <th>DNI</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>

        </thead>

        <tbody>

            <?php foreach ($lectores as $lector): ?>

                <tr>

                    <td><?= $lector['id'] ?></td>

                    <td><?= $lector['dni'] ?></td>

                    <td><?= $lector['nombre'] ?></td>

                    <td><?= $lector['apellido'] ?></td>

                    <td><?= $lector['correo'] ?></td>

                    <td><?= $lector['telefono'] ?></td>
                    <td>

                        <a href="?url=lectores/edit&id=<?= $lector['id'] ?>" class="btn btn-primary">
                            Editar
                        </a>

                        <a
                            href="?url=lectores/delete&id=<?= $lector['id'] ?>"
                            onclick="return confirm('¿Eliminar este lector?')"
                            class="btn btn-danger">
                            Eliminar
                        </a>

                    </td>

                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>

</div>

<?php require '../app/views/layouts/footer.php'; ?>
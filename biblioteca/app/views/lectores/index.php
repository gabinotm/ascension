<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="content">

    <div class="page-header">

        <h1>Lectores</h1>

        <a href="?url=lectores/create" class="btn">
            Nuevo Lector
        </a>

    </div>

    <table>

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

                        <a href="?url=lectores/edit&id=<?= $lector['id'] ?>">
                            Editar
                        </a>

                        |

                        <a
                            href="?url=lectores/delete&id=<?= $lector['id'] ?>"
                            onclick="return confirm('¿Eliminar este lector?')">
                            Eliminar
                        </a>

                    </td>

                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>

</div>

<?php require '../app/views/layouts/footer.php'; ?>
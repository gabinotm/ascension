<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="content">

    <div class="page-header">

        <h1>Préstamos</h1>

        < <a href="?url=lectores/create" class="btn">
            Nuevo Préstamo
            </a>

    </div>

    <table>

        <thead>

            <tr>

                <th>ID</th>
                <th>Lector</th>
                <th>Libro</th>
                <th>Fecha Préstamo</th>
                <th>Fecha Devolución</th>
                <th>Estado</th>
                <th>Acciones</th>

            </tr>

        </thead>

        <tbody>

            <?php foreach ($prestamos as $prestamo): ?>

                <tr>

                    <td><?= $prestamo['id'] ?></td>

                    <td>
                        <?= $prestamo['nombre'] ?>
                        <?= $prestamo['apellido'] ?>
                    </td>

                    <td>
                        <?= $prestamo['titulo'] ?>
                    </td>

                    <td>
                        <?= $prestamo['fecha_prestamo'] ?>
                    </td>

                    <td>
                        <?= $prestamo['fecha_devolucion'] ?>
                    </td>

                    <td>
                        <?= $prestamo['estado'] ?>
                    </td>
                    <td>

                        <a href="?url=prestamos/edit&id=<?= $prestamo['id'] ?>">
                            Editar
                        </a>

                        |

                        <a
                            href="?url=prestamos/delete&id=<?= $prestamo['id'] ?>"
                            onclick="return confirm('¿Eliminar préstamo?')">
                            Eliminar
                        </a>

                    </td>
                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>

</div>

<?php require '../app/views/layouts/footer.php'; ?>
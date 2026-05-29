<?php require '../app/views/layouts/header.php'; ?>

<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="content crud-page prestamos-page">


    <div class="page-header">

        <h1>Préstamos</h1>

        <div class="acciones-header">

            <a href="?url=scanner&destino=prestamos" class="btn btn-success">

                📷 Escanear Libro

            </a>

            <a href="?url=prestamos/create" class="btn btn-warning">

                Nuevo Prestamo

            </a>

        </div>

    </div>

    <div class="search-box">

        <input type="text" id="buscar" class="form-control" placeholder="Buscar préstamo...">

    </div>

    <table class="table">

        <thead>

            <tr>

                <th>ID</th>
                <th>Lector</th>
                <th>Libro</th>
                <th>Fecha Préstamo</th>
                <th>Fecha Devolución</th>
                <th>Fecha Entrega</th>
                <th>Estado</th>
                <th>Acciones</th>

            </tr>

        </thead>

        <tbody>

            <?php foreach($prestamos as $prestamo): ?>

            <?php

        if(!empty($prestamo['fecha_entrega']))
        {
            $estado = 'Devuelto';
        }
        elseif(strtotime($prestamo['fecha_devolucion']) < time())
        {
            $estado = 'Vencido';
        }
        else
        {
            $estado = 'Prestado';
        }

        ?>

            <tr>

                <td>
                    <?= $prestamo['id'] ?>
                </td>

                <td>
                    <?= $prestamo['nombre'] ?>
                    <?= $prestamo['apellido'] ?>
                </td>

                <td class="libro-columna">
                    <?= $prestamo['titulo'] ?>
                </td>

                <td>
                    <?= date('d/m/Y H:i', strtotime($prestamo['fecha_prestamo'])) ?>
                </td>

                <td>
                    <?= date('d/m/Y H:i', strtotime($prestamo['fecha_devolucion'])) ?>
                </td>

                <td>

                    <?php if(!empty($prestamo['fecha_entrega'])): ?>

                    <?= date('d/m/Y H:i', strtotime($prestamo['fecha_entrega'])) ?>

                    <?php else: ?>

                    -

                    <?php endif; ?>

                </td>

                <td class="estado">

                    <?php if($estado == 'Prestado'): ?>

                    <span class="badge badge-primary">
                        Prestado
                    </span>

                    <?php endif; ?>

                    <?php if($estado == 'Devuelto'): ?>

                    <span class="badge badge-success">
                        Devuelto
                    </span>

                    <?php endif; ?>

                    <?php if($estado == 'Vencido'): ?>

                    <span class="badge badge-danger">
                        Vencido
                    </span>

                    <?php endif; ?>

                </td>

                <td>

                    <div class="acciones">

                        <a href="?url=prestamos/edit&id=<?= $prestamo['id'] ?>" class="btn btn-primary">

                            Editar

                        </a>

                        <?php if(empty($prestamo['fecha_entrega'])): ?>

                        <a href="?url=prestamos/devolver&id=<?= $prestamo['id'] ?>" class="btn btn-success"
                            onclick="return confirm('¿Registrar devolución?')">

                            Devolver

                        </a>

                        <?php endif; ?>

                        <a href="?url=prestamos/delete&id=<?= $prestamo['id'] ?>" class="btn btn-danger"
                            onclick="return confirm('¿Eliminar préstamo?')">

                            Eliminar

                        </a>

                    </div>

                </td>

            </tr>

            <?php endforeach; ?>

        </tbody>

    </table>


</div>

<script>
const buscar = document.getElementById('buscar');

buscar.addEventListener('keyup', function() {

    let texto = this.value.toLowerCase();

    document.querySelectorAll('tbody tr').forEach(fila => {

        fila.style.display =
            fila.textContent.toLowerCase().includes(texto) ?
            '' :
            'none';

    });

});
</script>

<?php require '../app/views/layouts/footer.php'; ?>
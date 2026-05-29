<?php require '../app/views/layouts/header.php'; ?>

<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="content">
    <div class="form-card">
        <h1>Nuevo Préstamo</h1>

        <form method="POST" action="?url=prestamos/store">

            <div>

                <label>Lector</label>

                <select name="lector_id" class="form-control" required>

                    <?php foreach($lectores as $lector): ?>

                    <option value="<?= $lector['id'] ?>">

                        <?= $lector['nombre'] ?>
                        <?= $lector['apellido'] ?>

                    </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <br>

            <div class="form-group">

                <label>Libro</label>

                <select name="libro_id" class="form-control" required>


                    <?php foreach($libros as $libro): ?>

                    <option value="<?= $libro['id'] ?>" <?= $libroSeleccionado == $libro['id']
? 'selected'
: '' ?>>

                        <?= $libro['titulo'] ?>

                    </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <br>

            <div class="form-group">

                <label>Fecha y hora préstamo</label>

                <input type="datetime-local" name="fecha_prestamo" required>

            </div>

            <br>

            <div class="form-group">

                <label>Fecha y hora devolución</label>

                <input type="datetime-local" name="fecha_devolucion" required>

            </div>

            <br>

            <button type="submit" Class="btn btn-primary">

                Guardar

            </button>

        </form>
    </div>
</div>

<?php require '../app/views/layouts/footer.php'; ?>
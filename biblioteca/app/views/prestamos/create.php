<?php require '../app/views/layouts/header.php'; ?>

<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="content prestamos-page">

<div class="form-card">

    <div class="page-header">

        <h1>Nuevo Préstamo</h1>

        <a href="?url=prestamos" class="btn btn-danger">
            Volver
        </a>

    </div>

    <form method="POST" action="?url=prestamos/store">

        <!-- LECTOR -->

        <div class="form-group">

            <label>Lector</label>

            <div class="scanner-field">

                <select
                    name="lector_id"
                    id="lector_id"
                    class="form-control"
                    required>

                    <option value="">
                        Seleccionar lector
                    </option>

                    <?php foreach($lectores as $lector): ?>

                        <option value="<?= $lector['id'] ?>">

                            <?= $lector['dni'] ?>
                            -
                            <?= $lector['nombre'] ?>
                            <?= $lector['apellido'] ?>

                        </option>

                    <?php endforeach; ?>

                </select>

                <button
                    type="button"
                    id="abrirBuscadorLector"
                    class="btn btn-primary">

                    🔍

                </button>

            </div>

        </div>

        <!-- LIBRO -->

        <div class="form-group">

            <label>Libro</label>

            <div class="scanner-field">

                <select
                    name="libro_id"
                    id="libro_id"
                    class="form-control"
                    required>

                    <option value="">
                        Seleccionar libro
                    </option>

                    <?php foreach($libros as $libro): ?>

                        <option
                            value="<?= $libro['id'] ?>"
                            data-isbn="<?= $libro['isbn'] ?>"
                            <?= (
                                isset($libroSeleccionado)
                                &&
                                $libroSeleccionado ==
                                $libro['id']
                            )
                            ? 'selected'
                            : '' ?>>

                            <?= $libro['titulo'] ?>
                            (<?= $libro['cantidad_disponible'] ?> disponibles)

                        </option>

                    <?php endforeach; ?>

                </select>

                <button
                    type="button"
                    id="abrirScanner"
                    class="btn btn-success">

                    📷

                </button>

            </div>

        </div>

        <!-- FECHA PRÉSTAMO -->

        <div class="form-group">

            <label>Fecha y Hora Préstamo</label>

            <input
                type="datetime-local"
                name="fecha_prestamo"
                class="form-control"
                value="<?= date('Y-m-d\TH:i') ?>"
                required>

        </div>

        <!-- FECHA DEVOLUCIÓN -->

        <div class="form-group">

            <label>Fecha y Hora Devolución</label>

            <input
                type="datetime-local"
                name="fecha_devolucion"
                class="form-control"
                required>

        </div>

        <button
            type="submit"
            class="btn btn-primary">

            Guardar Préstamo

        </button>

    </form>

</div>


</div>

<!-- MODAL BUSCADOR DE LECTOR -->

<div id="modalLectores" class="scanner-modal">


<div class="scanner-box">

    <div class="scanner-header">

        <h3>Buscar Lector</h3>

        <button
            type="button"
            id="cerrarLectores"
            class="btn btn-danger">

            X

        </button>

    </div>

    <input
        type="text"
        id="buscarLector"
        class="form-control"
        placeholder="Buscar por DNI, nombre o apellido">

    <br>

    <div id="resultadoLectores"
         class="busqueda-lista"></div>

</div>


</div>

<!-- MODAL SCANNER -->

<div id="scannerModal" class="scanner-modal">


<div class="scanner-box">

    <div class="scanner-header">

        <h3>Escanear ISBN</h3>

        <button
            type="button"
            id="cerrarScanner"
            class="btn btn-danger">

            X

        </button>

    </div>

    <div id="reader"></div>

</div>


</div>

<script src="https://unpkg.com/html5-qrcode"></script>

<script>

const lectores = <?= json_encode($lectores) ?>;

/* ==========================
   BUSCADOR DE LECTORES
========================== */

const modalLectores =
document.getElementById('modalLectores');

document
.getElementById('abrirBuscadorLector')
.onclick = () =>
modalLectores.style.display = 'flex';

document
.getElementById('cerrarLectores')
.onclick = () =>
modalLectores.style.display = 'none';

const buscarLector =
document.getElementById('buscarLector');

const resultadoLectores =
document.getElementById('resultadoLectores');

buscarLector.addEventListener('keyup', function(){

    let texto =
    this.value.toLowerCase();

    resultadoLectores.innerHTML = '';

    lectores.forEach(lector => {

        if(

            lector.dni
            .toLowerCase()
            .includes(texto)

            ||

            lector.nombre
            .toLowerCase()
            .includes(texto)

            ||

            lector.apellido
            .toLowerCase()
            .includes(texto)

        ){

            let item =
            document.createElement('div');

            item.className =
            'busqueda-item';

            item.innerHTML =

                '<strong>' +
                lector.dni +
                '</strong> - ' +

                lector.nombre +
                ' ' +

                lector.apellido;

            item.onclick = () => {

                document
                .getElementById(
                    'lector_id'
                ).value =
                lector.id;

                modalLectores
                .style.display =
                'none';

            };

            resultadoLectores
            .appendChild(item);

        }

    });

});

/* ==========================
   SCANNER ISBN
========================== */

let html5QrCode;

const modalScanner =
document.getElementById('scannerModal');

document
.getElementById('abrirScanner')
.onclick = function(){

    modalScanner.style.display =
    'flex';

    html5QrCode =
    new Html5Qrcode("reader");

    Html5Qrcode.getCameras()
    .then(cameras => {

        if(cameras.length){

            html5QrCode.start(

                cameras[0].id,

                {
                    fps:10,
                    qrbox:250
                },

                (isbn) => {

                    let select =
                    document.getElementById(
                        'libro_id'
                    );

                    Array.from(
                        select.options
                    ).forEach(option => {

                        if(
                            option.dataset.isbn
                            == isbn
                        ){
                            option.selected =
                            true;
                        }

                    });

                    html5QrCode.stop();

                    modalScanner
                    .style.display =
                    'none';

                }

            );

        }

    });

};

document
.getElementById('cerrarScanner')
.onclick = function(){

    if(html5QrCode){

        html5QrCode.stop();

    }

    modalScanner.style.display =
    'none';

};

</script>

<?php require '../app/views/layouts/footer.php'; ?>

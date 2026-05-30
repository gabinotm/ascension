<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="content">

    <div class="form-card">

        <div class="page-header">

            <h1>Nuevo Libro</h1>

            <a
            href="?url=libros"
            class="btn btn-danger">

            Volver

            </a>

        </div>

        <form
        action="?url=libros/store"
        method="POST">

            <div class="form-group">

                <label>ISBN</label>

                <div class="isbn-group">

                    <input
                    type="text"
                    name="isbn"
                    id="isbn"
                    class="form-control"
                    required>

                    <button
                    type="button"
                    id="btn-escanear"
                    class="btn btn-success">

                    📷 Escanear

                    </button>

                </div>

            </div>

            <div class="form-group">

                <label>Título</label>

                <input
                type="text"
                name="titulo"
                class="form-control"
                required>

            </div>

            <div class="form-group">

                <label>Autor</label>

                <input
                type="text"
                name="autor"
                class="form-control"
                required>

            </div>

            <div class="form-group">

                <label>Cantidad Total</label>

                <input
                type="number"
                name="cantidad_total"
                class="form-control"
                min="1"
                value="1"
                required>

            </div>

            <div class="form-group">

                <label>Cantidad Disponible</label>

                <input
                type="number"
                name="cantidad_disponible"
                class="form-control"
                min="0"
                value="1"
                required>

            </div>

            <button
            type="submit"
            class="btn btn-primary">

            Guardar Libro

            </button>

        </form>

    </div>

</div>

<!-- MODAL ESCANER -->

<div id="modalScanner" class="modal-scanner">
    <div class="modal-content">
        <h3>Escanear ISBN</h3>
        <div id="reader"></div>

        <button
            type="button"
            id="cerrarScanner"
            class="btn btn-danger">
            Cerrar
        </button>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>

<script>

const modal =
document.getElementById('modalScanner');

const btnEscanear =
document.getElementById('btn-escanear');

const cerrarScanner =
document.getElementById('cerrarScanner');

let html5QrCode;

btnEscanear.addEventListener('click', function(){

    modal.style.display = 'flex';

    html5QrCode = new Html5Qrcode("reader");

    Html5Qrcode.getCameras().then(devices => {

        if(devices && devices.length){

            html5QrCode.start(

                devices[0].id,

                {
                    fps:10,
                    qrbox:250
                },

                (decodedText) => {

                    document
                    .getElementById('isbn')
                    .value = decodedText;

                    html5QrCode.stop();

                    modal.style.display = 'none';

                }

            );

        }

    });

});

cerrarScanner.addEventListener('click', function(){

    if(html5QrCode){

        html5QrCode.stop();

    }

    modal.style.display = 'none';

});

</script>

<?php require '../app/views/layouts/footer.php'; ?>
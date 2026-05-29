<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="content scanner-page">

    <div class="scanner-card">

        <h1>Escanear Código</h1>

        <p>
            Apunte la cámara al código de barras o QR.
        </p>

        <div id="reader"></div>

    </div>

</div>

<script src="https://unpkg.com/html5-qrcode"></script>

<script>

const destino =
'<?= $_GET['destino'] ?? 'libros' ?>';

function onScanSuccess(codigo)
{
    window.location =
    '?url=' + destino +
    '/buscar&isbn=' +
    encodeURIComponent(codigo);
}

new Html5QrcodeScanner(
    "reader",
    {
        fps:10,
        qrbox:250
    }
).render(onScanSuccess);

</script>

<?php require '../app/views/layouts/footer.php'; ?>
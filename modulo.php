<?php
require_once 'config/conexion.php';

include 'includes/header.php';
include 'includes/navbar.php';

/* =========================================================
FUNCION YOUTUBE
========================================================= */
function obtenerYoutubeID($url)
{
    $patrones = [
        '/youtube\.com\/watch\?v=([^\&\?\/]+)/',
        '/youtube\.com\/embed\/([^\&\?\/]+)/',
        '/youtube\.com\/shorts\/([^\&\?\/]+)/',
        '/youtu\.be\/([^\&\?\/]+)/'
    ];
    foreach ($patrones as $patron) {
        if (preg_match($patron, $url, $matches)) {
            return $matches[1];
        }
    }
    return '';
}

/* =========================================================
SUBCATEGORIA
========================================================= */
$idSubcategoria = isset($_GET['subcategoria']) ? intval($_GET['subcategoria']) : 0;

/* =========================================================
INFO SUBCATEGORIA
========================================================= */
$querySubcategoria = "
    SELECT subcategorias.*, categorias.nombre AS categoria
    FROM subcategorias
    LEFT JOIN categorias ON subcategorias.categoria_id = categorias.id
    WHERE subcategorias.id='$idSubcategoria'
";
$resultSubcategoria = mysqli_query($conn, $querySubcategoria);
$subcategoria = mysqli_fetch_assoc($resultSubcategoria);

if (!$subcategoria) {
    die('Subcategoría no encontrada');
}

/* =========================================================
PUBLICACIONES
========================================================= */
$queryPublicaciones = "
    SELECT publicaciones.*, usuarios.nombres AS autor
    FROM publicaciones
    LEFT JOIN usuarios ON publicaciones.usuario_id = usuarios.id
    WHERE publicaciones.subcategoria_id='$idSubcategoria'
    ORDER BY publicaciones.id DESC
";
$resultPublicaciones = mysqli_query($conn, $queryPublicaciones);
?>

<div class="container-fluid px-3 px-md-4 py-4 py-md-5 layout-main-content">
    <!-- HEADER -->
    <div class="mb-4 mb-md-5 text-center text-md-start">
        <span class="badge bg-secondary text-uppercase tracking-wider px-3 py-2 mb-2">
            <?php echo $subcategoria['categoria']; ?>
        </span>
        <h1 class="display-6 fw-bold text-dark m-0">
            <?php echo $subcategoria['nombre']; ?>
        </h1>
    </div>

    <!-- BUSCADOR + FILTRO -->
    <div class="search-bar-modern mb-4 mb-md-5">
        <div class="row g-3 align-items-center">
            <div class="col-lg-4 col-md-6">
                <label for="filtroTipo" class="form-label small fw-semibold text-secondary">Filtrar por tipo</label>
                <select id="filtroTipo" class="form-select search-select-modern">
                    <option value="">Todos los tipos</option>
                    <option value="herramientas">Herramientas</option>
                    <option value="tutoriales">Tutoriales</option>
                    <option value="actividades">Actividades</option>
                    <option value="eventos">Eventos</option>
                    <option value="archivos">Archivos</option>
                    <option value="alertas">Alertas</option>
                    <option value="galeria">Galería</option>
                    <option value="reuniones">Reuniones</option>
                    <option value="informativo">Informativo</option>
                </select>
            </div>
        </div>
    </div>

    <!-- GRID -->
    <div class="row g-3 g-md-4" id="contenedorPublicaciones">
        <?php while ($row = mysqli_fetch_assoc($resultPublicaciones)) { ?>
            <!-- CARD -->
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card-custom h-100 p-3">

                    <!-- IMAGEN / PREVIEW VIDEO -->
                    <?php
                    $video = trim($row['video']);
                    $thumbnail = '';
                    $embedVideoUrl = '';

                    if ($row['imagen'] != '') {
                        $thumbnail = 'img/' . $row['imagen'];
                    } elseif (strpos($video, 'youtube.com') !== false || strpos($video, 'youtu.be') !== false) {
                        $youtubeID = obtenerYoutubeID($video);
                        $thumbnail = 'https://img.youtube.com/vi/' . $youtubeID . '/hqdefault.jpg';
                        $embedVideoUrl = 'https://www.youtube.com/embed/' . $youtubeID;
                    } elseif (strpos($video, 'drive.google.com') !== false) {
                        $thumbnail = 'https://cdn-icons-png.flaticon.com/512/2965/2965300.png';
                        preg_match('/\/d\/(.*?)\//', $video, $matches);
                        $driveID = $matches[1] ?? '';
                        $embedVideoUrl = 'https://drive.google.com/file/d/' . $driveID . '/preview';
                    }
                    ?>

                    <?php if ($thumbnail != '') { ?>
                        <div class="position-relative overflow-hidden rounded-4 mb-3 image-wrapper">
                            <div class="video-preview-card btn-ver-detalles-trigger" style="cursor: pointer;">
                                <img src="<?php echo $thumbnail; ?>" class="img-fluid w-100 object-fit-cover" style="height: 160px;" loading="lazy">
                                <?php if ($video != '') { ?>
                                    <div class="video-play-overlay">
                                        <i class="bi bi-play-fill text-white fs-2"></i>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- BADGE -->
                    <div class="mb-2">
                        <span class="badge bg-light text-primary border border-primary-subtle rounded-pill px-3 py-1.5 small fw-semibold text-capitalize">
                            <?php echo $row['tipo_publicacion']; ?>
                        </span>
                    </div>

                    <!-- META INFO (AUTOR / FECHA) -->
                    <div class="d-flex flex-wrap gap-2 mb-2 small text-muted">
                        <span class="d-flex align-items-center gap-1">
                            <i class="bi bi-person-circle"></i>
                            <?php echo htmlspecialchars($row['autor'] ?? 'Admin'); ?>
                        </span>
                        <span class="text-secondary-subtle">•</span>
                        <span class="d-flex align-items-center gap-1">
                            <i class="bi bi-calendar3"></i>
                            <?php echo date('d/m/Y', strtotime($row['fecha_registro'])); ?>
                        </span>
                    </div>

                    <!-- FECHAS EVENTOS -->
                    <?php
                    $esTemporal = in_array($row['tipo_publicacion'], ['eventos', 'reuniones', 'actividades']);
                    if ($esTemporal) {
                        $inicioEvento = new DateTime($row['fecha_inicio']);
                        $finEvento = $row['fecha_fin'] != '' ? new DateTime($row['fecha_fin']) : $inicioEvento;
                        $diasEvento = $inicioEvento->diff($finEvento)->days + 1;
                    ?>
                        <div class="evento-fecha-modern mb-2 p-2 rounded-3 bg-light">
                            <i class="bi bi-calendar-event text-primary"></i>
                            <span class="fw-medium text-dark small">
                                <?php echo date('d/m/Y', strtotime($row['fecha_inicio'])); ?>
                                <?php if ($row['fecha_fin'] != '') {
                                    echo ' - ' . date('d/m/Y', strtotime($row['fecha_fin']));
                                } ?>
                            </span>
                            <?php if ($diasEvento >= 2) { ?>
                                <span class="badge bg-primary-subtle text-primary ms-auto small"><?php echo $diasEvento; ?> días</span>
                            <?php } ?>
                        </div>
                    <?php } ?>

                    <!-- CONTENIDO -->
                    <div class="flex-grow-1">
                        <h5 class="fw-bold text-dark mb-2 text-line-clamp-2"><?php echo htmlspecialchars($row['titulo']); ?></h5>
                        <p class="text-secondary small mb-3 text-line-clamp-3">
                            <?php echo substr(strip_tags($row['descripcion']), 0, 120); ?>...
                        </p>
                    </div>

                    <!-- COUNTDOWN -->
                    <?php if ($esTemporal) { ?>
                        <div class="countdown-box mt-auto mb-3"
                            data-fecha="<?php echo trim($row['fecha_inicio'] . ' ' . $row['hora']); ?>"
                            data-tipo="<?php echo $row['tipo_publicacion']; ?>">
                            <div class="placeholder-glow"><span class="placeholder col-12 rounded-4" style="height: 55px;"></span></div>
                        </div>
                    <?php } ?>

                    <!-- ACCIONES -->
                    <div class="mt-auto pt-2 border-top d-flex gap-2">
                        <button class="btn btn-primary btn-sm rounded-pill flex-grow-1 btn-ver-detalles"
                            data-titulo="<?php echo htmlspecialchars($row['titulo']); ?>"
                            data-tipo="<?php echo ucfirst($row['tipo_publicacion']); ?>"
                            data-autor="<?php echo htmlspecialchars($row['autor'] ?? 'Admin'); ?>"
                            data-fecha="<?php echo date('d/m/Y h:i A', strtotime($row['fecha_registro'])); ?>"
                            data-descripcion="<?php echo htmlspecialchars($row['descripcion']); ?>"
                            data-link="<?php echo htmlspecialchars($row['link']); ?>"
                            data-archivo="<?php echo htmlspecialchars($row['archivo']); ?>"
                            data-video="<?php echo $embedVideoUrl; ?>"
                            data-thumbnail="<?php echo $thumbnail; ?>">
                            Ver más
                        </button>

                        <?php if (!empty(trim($row['link']))) { ?>
                            <a href="<?php echo htmlspecialchars($row['link']); ?>"
                                target="_blank"
                                class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                Ir
                            </a>
                        <?php } ?>
                    </div>

                </div>
            </div>
        <?php } ?>
    </div>
</div>

<!-- =========================================================
MODAL GENERAL TOTALMENTE RESPONSIVO (ÚNICO)
========================================================= -->
<div class="modal fade modal-modern-custom" id="modalDetallePublicacion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <span id="modalDetalleBadge" class="badge bg-light text-primary border border-primary-subtle rounded-pill px-3 py-1.5 fw-semibold"></span>
                <button class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-3">

                <!-- SECCIÓN DE VIDEO EMERGENTE (RESPONSIVE RATIO 16:9) -->
                <div id="modalDetalleVideoWrapper" class="mb-3 d-none">
                    <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-sm bg-black">
                        <iframe id="modalDetalleIframe" src="" title="Video Player" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>

                <!-- IMAGEN ESTÁTICA ALTERNATIVA -->
                <div id="modalDetalleImgWrapper" class="mb-3 d-none">
                    <img id="modalDetalleImg" src="" class="img-fluid rounded-4 w-100 object-fit-cover" style="max-height: 280px;">
                </div>

                <!-- CONTENIDO INFORMATIVO -->
                <h3 id="modalDetalleTitulo" class="fw-bold text-dark mb-2 fs-4 fs-md-3"></h3>

                <div class="d-flex flex-wrap gap-2 gap-sm-3 mb-3 small text-muted bg-light p-2 rounded-3">
                    <span class="d-flex align-items-center gap-1">
                        <i class="bi bi-person-circle text-secondary"></i>
                        <span id="modalDetalleAutor"></span>
                    </span>
                    <span class="d-flex align-items-center gap-1">
                        <i class="bi bi-clock text-secondary"></i>
                        <span id="modalDetalleFecha"></span>
                    </span>
                </div>

                <div class="text-secondary lh-base mb-4 small fs-md-6" id="modalDetalleDescripcion" style="white-space: pre-line;"></div>

                <!-- SECCIÓN ADJUNTOS Y LINKS -->
                <div id="modalDetalleAdjuntos" class="d-none border-top pt-3">
                    <h6 class="fw-bold text-dark mb-2 small"><i class="bi bi-paperclip"></i> Recursos y Enlaces:</h6>
                    <div class="d-flex flex-column d-sm-flex flex-sm-row gap-2">
                        <a id="modalDetalleBtnLink" href="" target="_blank" class="btn btn-primary rounded-pill px-4 btn-sm">
                            <i class="bi bi-box-arrow-up-right me-1"></i> Abrir Enlace Externo
                        </a>
                        <a id="modalDetalleBtnArchivo" href="" target="_blank" class="btn btn-outline-dark rounded-pill px-4 btn-sm">
                            <i class="bi bi-file-earmark-arrow-down me-1"></i> Descargar Archivo Adjunto
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JAVASCRIPT CORREGIDO -->
<script>
    const filtroTipo = document.getElementById('filtroTipo');
    const contenedor = document.getElementById('contenedorPublicaciones');
    let countdownInterval = null;

    function parsearFecha(fechaTexto) {
        const partes = fechaTexto.split(' ');
        if (partes.length < 2) return null;
        const fechaArray = partes[0].split('-');
        const horaArray = partes[1].split(':');
        if (fechaArray.length < 3 || horaArray.length < 2) return null;

        return new Date(
            parseInt(fechaArray[0]),
            parseInt(fechaArray[1]) - 1,
            parseInt(fechaArray[2]),
            parseInt(horaArray[0]),
            parseInt(horaArray[1]),
            0
        ).getTime();
    }

    function actualizarTodosLosCountdowns() {
        const ahora = new Date().getTime();
        const boxes = document.querySelectorAll('.countdown-box');

        if (boxes.length === 0 && countdownInterval) {
            clearInterval(countdownInterval);
            countdownInterval = null;
            return;
        }

        boxes.forEach(box => {
            const fechaTexto = box.getAttribute('data-fecha');
            const tipoPublicacion = box.getAttribute('data-tipo') || 'evento';
            const fechaObjetivo = parsearFecha(fechaTexto);

            if (!fechaObjetivo || isNaN(fechaObjetivo)) {
                box.innerHTML = '';
                return;
            }

            const diferencia = fechaObjetivo - ahora;

            if (diferencia <= 0) {
                let sufijo = 'finalizado';
                if (['actividades', 'reuniones'].includes(tipoPublicacion)) sufijo = 'finalizada';
                let nombreLimpio = tipoPublicacion.replace(/es$|s$/, "");

                box.innerHTML = `
                <div class="alert alert-light border border-danger-subtle text-danger rounded-3 text-center py-2 px-3 m-0 small fw-semibold text-capitalize">
                    <i class="bi bi-calendar-x-fill me-1"></i> ${nombreLimpio} ${sufijo}
                </div>
            `;
                return;
            }

            const dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));
            const horas = Math.floor((diferencia % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diferencia % (1000 * 60 * 60)) / (1000 * 60));
            const segundos = Math.floor((diferencia % (1000 * 60)) / 1000);

            box.innerHTML = `
            <div class="countdown-modern">
                <div class="countdown-grid">
                    <div class="countdown-item"><div class="countdown-number">${dias}</div><div class="countdown-label">Días</div></div>
                    <div class="countdown-item"><div class="countdown-number">${horas}</div><div class="countdown-label">Horas</div></div>
                    <div class="countdown-item"><div class="countdown-number">${minutes}</div><div class="countdown-label">Min</div></div>
                    <div class="countdown-item"><div class="countdown-number">${segundos}</div><div class="countdown-label">Seg</div></div>
                </div>
            </div>
        `;
        });
    }

    function iniciarCronometroGlobal() {
        if (countdownInterval) clearInterval(countdownInterval);
        actualizarTodosLosCountdowns();
        countdownInterval = setInterval(actualizarTodosLosCountdowns, 1000);
    }

    // Disparador Unificado al hacer Click
    document.addEventListener('click', function(e) {
        const trigger = e.target.closest('.btn-ver-detalles') || e.target.closest('.btn-ver-detalles-trigger');
        if (!trigger) return;

        // Si hicieron click en la imagen, buscamos el botón hermano de su tarjeta para obtener la data
        const btn = trigger.classList.contains('btn-ver-detalles') ? trigger : trigger.closest('.card-custom').querySelector('.btn-ver-detalles');
        if (!btn) return;

        const titulo = btn.getAttribute('data-titulo');
        const tipo = btn.getAttribute('data-tipo');
        const autor = btn.getAttribute('data-autor');
        const fecha = btn.getAttribute('data-fecha');
        const descripcion = btn.getAttribute('data-descripcion');
        const link = btn.getAttribute('data-link');
        const archivo = btn.getAttribute('data-archivo');
        const videoUrl = btn.getAttribute('data-video');
        const thumbnail = btn.getAttribute('data-thumbnail');

        document.getElementById('modalDetalleTitulo').innerText = titulo;
        document.getElementById('modalDetalleBadge').innerText = tipo;
        document.getElementById('modalDetalleAutor').innerText = autor;
        document.getElementById('modalDetalleFecha').innerText = fecha;
        document.getElementById('modalDetalleDescripcion').innerText = descripcion;

        // CONTROL INTELIGENTE DE VIDEO EMBED vs IMAGEN FIJA
        const videoWrapper = document.getElementById('modalDetalleVideoWrapper');
        const iframe = document.getElementById('modalDetalleIframe');
        const imgWrapper = document.getElementById('modalDetalleImgWrapper');
        const imgTag = document.getElementById('modalDetalleImg');

        if (videoUrl && videoUrl.trim() !== '') {
            iframe.src = videoUrl;
            videoWrapper.classList.remove('d-none');
            imgWrapper.classList.add('d-none'); // Ocultamos la imagen si hay video prioritario
        } else if (thumbnail && thumbnail.trim() !== '') {
            iframe.src = '';
            videoWrapper.classList.add('d-none');
            imgTag.src = thumbnail;
            imgWrapper.classList.remove('d-none');
        } else {
            iframe.src = '';
            videoWrapper.classList.add('d-none');
            imgWrapper.classList.add('d-none');
        }

        // BOTONES CONDICIONALES INTERNOS
        const divAdjuntos = document.getElementById('modalDetalleAdjuntos');
        const btnLink = document.getElementById('modalDetalleBtnLink');
        const btnArchivo = document.getElementById('modalDetalleBtnArchivo');
        let tieneAdjunto = false;

        if (link && link.trim() !== '') {
            btnLink.href = link;
            btnLink.classList.remove('d-none');
            tieneAdjunto = true;
        } else {
            btnLink.classList.add('d-none');
        }

        if (archivo && archivo.trim() !== '') {
            btnArchivo.href = 'img/' + archivo;
            btnArchivo.classList.remove('d-none');
            tieneAdjunto = true;
        } else {
            btnArchivo.classList.add('d-none');
        }

        if (tieneAdjunto) divAdjuntos.classList.remove('d-none');
        else divAdjuntos.classList.add('d-none');

        const m = new bootstrap.Modal(document.getElementById('modalDetallePublicacion'));
        m.show();
    });

    // Limpieza absoluta al cerrar la ventana emergente para frenar audios/videos en segundo plano
    document.getElementById('modalDetallePublicacion').addEventListener('hidden.bs.modal', function() {
        document.getElementById('modalDetalleIframe').src = '';
    });

    function cargarPublicaciones() {
        const tipo = filtroTipo.value;
        fetch(`buscar_publicaciones.php?buscar=&tipo=${tipo}&subcategoria=<?php echo $idSubcategoria; ?>`)
            .then(response => response.text())
            .then(data => {
                contenedor.innerHTML = data;
                iniciarCronometroGlobal();
            });
    }

    filtroTipo.addEventListener('change', cargarPublicaciones);
    window.addEventListener('DOMContentLoaded', iniciarCronometroGlobal);
</script>

<!-- CSS CORREGIDO CON CAPAS DE INTERFAZ (Z-INDEX) Y RESPONSIVE -->
<style>
    /* Solución al solapamiento con Navbar y menúes fijos */
    .layout-main-content {
        position: relative;
        z-index: 1;
        /* Mantiene las tarjetas abajo de la jerarquía global */
    }

    /* =========================================================
SOLUCIÓN DE SUPERPOSICIÓN Y CORTE RESPONSIVE
========================================================= */

    /* Forzar al contenedor del modal a estar por encima de la barra de navegación */
    .modal-modern-custom {
        z-index: 99999 !important;
        /* Un valor exageradamente alto para ganarle a tu menú azul */
        background-color: rgba(15, 23, 42, 0.5) !important;
        /* Fondo oscuro traslúcido para dar enfoque */
    }

    /* Forzar al fondo del modal (backdrop) a estar en la capa correcta si Bootstrap lo autogenera */
    .modal-backdrop {
        z-index: 99990 !important;
    }

    /* Ajustes de adaptabilidad para pantallas móviles y evitar que se pegue al techo */
    .modal-modern-custom .modal-dialog {
        margin: 1.5rem auto;
        max-width: 95%;
    }

    /* Control estricto del scroll interno */
    .modal-modern-custom .modal-body {
        /* Limitamos la altura máxima restando el espacio del header del modal para que nunca se desborde */
        max-height: calc(100vh - 180px) !important;
        overflow-y: auto !important;
        -webkit-overflow-scrolling: touch;
        padding: 1.25rem;
    }

    /* Asegurar que las imágenes dentro del modal no rompan el contenedor */
    #modalDetalleImg {
        width: 100%;
        height: auto;
        max-height: 250px;
        object-fit: cover;
        border-radius: 12px;
    }

    /* Contenedor principal que se ajusta a la pantalla */
    .container-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
        padding: 20px 0;
    }

    /* Tarjetas compactas */
    .card-tutorial {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        transition: transform 0.2s, box-shadow 0.2s;
        overflow: hidden;
    }

    .card-tutorial:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .card-tutorial img {
        height: 140px;
        width: 100%;
        object-fit: cover;
    }

    .card-body-compact {
        padding: 15px;
    }

    @media (min-width: 576px) {
        .modal-modern-custom .modal-dialog {
            max-width: 540px;
        }
    }

    @media (min-width: 768px) {
        .modal-modern-custom .modal-dialog {
            max-width: 720px;
        }
    }

    /* Manejo óptimo de scrolls internos en móviles */
    .modal-modern-custom .modal-body {
        max-height: calc(100vh - 160px);
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
        padding: 1.25rem;
    }

    /* Estructura Base de Tarjetas */
    .card-custom {
        background: #ffffff;
        border: 1px solid #eef2f6;
        border-radius: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .card-custom:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.06);
    }

    /* Filtros */
    .search-bar-modern {
        background: #ffffff;
        padding: 20px;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
    }

    .search-select-modern {
        height: 46px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 14px;
        background-color: #f8fafc;
    }

    /* Previsualizadores Multimedia */
    .video-preview-card {
        position: relative;
        overflow: hidden;
        border-radius: 12px;
    }

    .video-preview-card img {
        transition: transform 0.4s ease;
    }

    .video-preview-card:hover img {
        transform: scale(1.03);
    }

    .video-play-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(15, 23, 42, 0.35);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.25s ease;
    }

    .video-preview-card:hover .video-play-overlay {
        opacity: 1;
    }

    /* Recorte de seguridad para textos largos */
    .text-line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .text-line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Grid de la Cuenta Regresiva */
    .countdown-modern {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 8px;
    }

    .countdown-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 4px;
    }

    .countdown-item {
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 8px;
        padding: 4px 2px;
        text-align: center;
    }

    .countdown-number {
        font-size: 14px;
        font-weight: 700;
        color: #1e3a8a;
        line-height: 1;
    }

    .countdown-label {
        font-size: 9px;
        text-transform: uppercase;
        color: #64748b;
        margin-top: 1px;
    }

    .evento-fecha-modern {
        display: flex;
        align-items: center;
        gap: 6px;
    }
</style>

<?php include 'includes/footer.php'; ?>
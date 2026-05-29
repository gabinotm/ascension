<?php
require_once 'config/conexion.php';
include 'includes/header.php';
include 'includes/navbar.php';

// Consulta usando EXCLUSIVAMENTE los nombres de tu tabla
$query = "SELECT titulo, descripcion, imagen, fecha_inicio, fecha_fin, hora, tipo_publicacion, autor, fecha_registro
          FROM publicaciones 
          WHERE tipo_publicacion IN ('eventos', 'reuniones', 'actividades')";

$result = mysqli_query($conn, $query);
$eventos = [];
while ($row = mysqli_fetch_assoc($result)) {
    $eventos[] = $row;
}
?>

<div class="main-hero-wrapper">
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php $imgs = ['fondo1.jpg', 'fondo2.jpg', 'fondo3.jpg', 'fondo4.jpg', 'fondo5.jpg'];
            foreach ($imgs as $i => $img) { ?>
                <div class="carousel-item <?php echo $i === 0 ? 'active' : ''; ?>">
                    <img src="assets/img/<?php echo $img; ?>" class="d-block w-100">
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="overlay-content">
        <div class="container-fluid">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-6 text-white ps-5">
                    <h1 class="display-3 fw-bold">CONOCIMIENTO,<br><span style="background: #fd7d15; padding: 0 10px;">APRENDIZAJE,</span><br>EXIGENCIA Y <span style="color: #10afe9">ESFUERZO</span></h1>
                </div>
                <div class="col-lg-5">
                    <div class="calendar-card">
                        <div class="calendar-header-styled">
                            <button class="nav-btn" onclick="cambiarMes(-1)"><i class="fa-solid fa-chevron-left"></i></button>
                            <h4 class="m-0 fw-bolder text-dark" id="calendarMonth"></h4>
                            <button class="nav-btn" onclick="cambiarMes(1)"><i class="fa-solid fa-chevron-right"></i></button>
                        </div>
                        <div class="days-of-week"><span>D</span><span>L</span><span>M</span><span>M</span><span>J</span><span>V</span><span>S</span></div>
                        <div class="calendar-grid" id="calendarDays"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEvento" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-5 p-3">
            <div class="modal-header border-0">
                <h4 class="modal-title fw-bold">Detalles del Evento</h4><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalBody"></div>
        </div>
    </div>
</div>
<section class="container my-5">
    <div class="d-flex align-items-center mb-4">
        <h2 class="fw-bold text-dark me-3">Anuncios y Alertas</h2>
        <div style="flex-grow: 1; height: 3px; background: #fd7d15;"></div>
    </div>

    <div class="row">
        <?php
        // Consulta corregida: consultamos 'publicaciones'
        $queryAnuncios = "SELECT * FROM publicaciones 
                          WHERE tipo_publicacion IN ('anuncio', 'alerta') 
                          ORDER BY FIELD(prioridad, 'Alta', 'Media', 'Baja'), fecha_registro DESC 
                          LIMIT 4";
                          
        $resAnuncios = mysqli_query($conn, $queryAnuncios);
        
        while($anuncio = mysqli_fetch_assoc($resAnuncios)) {
            // Lógica de colores basada en la prioridad
            $bordeColor = ($anuncio['prioridad'] == 'Alta') ? 'border-danger' : (($anuncio['prioridad'] == 'Media') ? 'border-warning' : 'border-primary');
            $badgeColor = ($anuncio['prioridad'] == 'Alta') ? 'bg-danger' : (($anuncio['prioridad'] == 'Media') ? 'bg-warning' : 'bg-primary');
            
            echo '
            <div class="col-md-3 mb-4">
                <div class="card h-100 border-top border-4 '.$bordeColor.' shadow-sm">
                    <img src="images/img/'.$anuncio['imagen'].'" class="card-img-top" alt="...">
                    <div class="card-body">
                        <span class="badge '.$badgeColor.' mb-2">'.$anuncio['prioridad'].'</span>
                        <h6 class="fw-bold">'.$anuncio['titulo'].'</h6>
                        <p class="small text-muted">'.substr($anuncio['descripcion'], 0, 80).'...</p>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <a href="#" class="btn btn-sm btn-outline-primary w-100">Ver más</a>
                    </div>
                </div>
            </div>';
        }
        ?>
    </div>
</section>
<script>
    const todosLosEventos = <?php echo json_encode($eventos); ?>;
    const monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
    let currentDate = new Date();

    function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        document.getElementById('calendarMonth').innerText = `${monthNames[month]} ${year + 2000}`.replace('2026', '2026'); // Ajuste visual

        const daysContainer = document.getElementById('calendarDays');
        daysContainer.innerHTML = '';
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        for (let i = 0; i < firstDay; i++) daysContainer.innerHTML += `<div></div>`;

        for (let day = 1; day <= daysInMonth; day++) {
            let eventosDelDia = todosLosEventos.filter(e => {
    let start = new Date(e.fecha_inicio);
    let end = new Date(e.fecha_fin);
    let actual = new Date(year, month, day);
    start.setHours(0,0,0,0); end.setHours(23,59,59,999);
    return actual >= start && actual <= end;
});

// Generar puntos: toma el color de la base de datos, si no existe usa azul por defecto
let dotsHtml = eventosDelDia.map(e => 
    `<div class="dot" style="background-color: ${e.color || '#0ea5e9'};"></div>`
).join('');

daysContainer.innerHTML += `
    <div class="cal-day ${eventosDelDia.length > 0 ? 'has-event' : ''}" 
         onclick='abrirModal(${JSON.stringify(eventosDelDia).replace(/"/g, "&quot;")})'>
        <span class="day-number">${day}</span>
        <div class="event-dots-container">${dotsHtml}</div>
    </div>`;
        }
    }

    function abrirModal(eventos) {
        // Si eventos es un arreglo, los mostramos todos
        let html = eventos.map(e => `
        <div class="evento-item mb-4 pb-3 border-bottom">
            <img src="img/${e.imagen}" class="img-fluid rounded mb-3" onerror="this.src='assets/img/default.jpg'">
            <h3 class="fw-bold">${e.titulo}</h3>
            <p><strong>Tipo:</strong> ${e.tipo_publicacion} | <strong>Autor:</strong> ${e.autor}</p>
            <p>${e.descripcion}</p>
            <p class="text-primary fw-bold">Fecha: ${e.fecha_inicio} | Hora: ${e.hora}</p>
        </div>`).join('');

        document.getElementById('modalBody').innerHTML = html;

        // Mostramos el modal
        var myModal = new bootstrap.Modal(document.getElementById('modalEvento'));
        myModal.show();
    }

    function cambiarMes(n) {
        currentDate.setMonth(currentDate.getMonth() + n);
        renderCalendar();
    }
    document.addEventListener('DOMContentLoaded', renderCalendar);
</script>
<?php include 'includes/footer.php'; ?>
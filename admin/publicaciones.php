<?php

include '../includes/header.php';
include '../includes/navbar.php';
require_once '../config/conexion.php';

/* =========================================================
CATEGORÍAS
========================================================= */

$queryCategorias = "
SELECT *
FROM categorias
WHERE estado='Activo'
ORDER BY nombre ASC
";

$resultCategorias = mysqli_query($conn,$queryCategorias);

/* =========================================================
GUARDAR
========================================================= */

if(isset($_POST['guardar'])){

    $titulo = mysqli_real_escape_string(
        $conn,
        $_POST['titulo']
    );

    $descripcion = mysqli_real_escape_string(
        $conn,
        $_POST['descripcion']
    );

    $link = $_POST['link'] ?? '';

    $video = $_POST['video'] ?? '';

    $fecha_inicio = $_POST['fecha_inicio'] ?? null;

    $fecha_fin = $_POST['fecha_fin'] ?? null;

    $hora = $_POST['hora'] ?? null;

    $estado = $_POST['estado'] ?? '';

    $prioridad = $_POST['prioridad'] ?? '';

    $categoria_id = $_POST['categoria_id'];

    $usuario_id = $_SESSION['id'];

    /* IMAGEN */

    $imagen = '';

    if(isset($_FILES['imagen']) && $_FILES['imagen']['name'] != ''){

        $nombreImagen =
        time().'_'.$_FILES['imagen']['name'];

        move_uploaded_file(
            $_FILES['imagen']['tmp_name'],
            '../uploads/'.$nombreImagen
        );

        $imagen = $nombreImagen;

    }

    /* ARCHIVO */

    $archivo = '';

    if(isset($_FILES['archivo']) && $_FILES['archivo']['name'] != ''){

        $nombreArchivo =
        time().'_'.$_FILES['archivo']['name'];

        move_uploaded_file(
            $_FILES['archivo']['tmp_name'],
            '../uploads/'.$nombreArchivo
        );

        $archivo = $nombreArchivo;

    }

    $query = "

    INSERT INTO publicaciones
    (
        titulo,
        descripcion,
        imagen,
        archivo,
        video,
        link,
        fecha_inicio,
        fecha_fin,
        hora,
        estado,
        prioridad,
        categoria_id,
        usuario_id
    )

    VALUES
    (
        '$titulo',
        '$descripcion',
        '$imagen',
        '$archivo',
        '$video',
        '$link',
        '$fecha_inicio',
        '$fecha_fin',
        '$hora',
        '$estado',
        '$prioridad',
        '$categoria_id',
        '$usuario_id'
    )

    ";

    mysqli_query($conn,$query);

    header('Location: publicaciones.php?success=1');

}

/* =========================================================
LISTAR
========================================================= */

$queryPublicaciones = "

SELECT publicaciones.*,

categorias.nombre AS categoria,

categorias.tipo_modulo

FROM publicaciones

LEFT JOIN categorias
ON publicaciones.categoria_id = categorias.id

ORDER BY publicaciones.id DESC

";

$resultPublicaciones = mysqli_query(
    $conn,
    $queryPublicaciones
);

?>

<section class="page-title">

<h2>
Publicaciones
</h2>

<p>
Sistema dinámico institucional
</p>

</section>

<div class="top-actions">

<button
class="btn-add"
onclick="openModal()"
>

<i class="fa-solid fa-plus"></i>

Nueva Publicación

</button>

</div>

<section class="admin-table">

<table>

<thead>

<tr>
<th>Título</th>
<th>Categoría</th>
<th>Tipo</th>
<th>Fecha</th>
</tr>

</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($resultPublicaciones)){ ?>

<tr>

<td>
<?php echo $row['titulo']; ?>
</td>

<td>
<?php echo $row['categoria']; ?>
</td>

<td>
<?php echo $row['tipo_modulo']; ?>
</td>

<td>
<?php echo $row['fecha_registro']; ?>
</td>

</tr>

<?php } ?>

</tbody>

</table>

</section>

<!-- MODAL -->

<div class="modal" id="publicacionModal">

<div class="modal-content large-modal">

<div class="modal-header">

<h3>
Nueva Publicación
</h3>

<button
class="close-btn"
onclick="closeModal()"
>
×
</button>

</div>

<form
method="POST"
enctype="multipart/form-data"
class="admin-form"
>

<div class="form-group">

<label>
Categoría
</label>

<select
name="categoria_id"
id="categoriaSelect"
required
>

<option value="">
Seleccione
</option>

<?php while($cat = mysqli_fetch_assoc($resultCategorias)){ ?>

<option
value="<?php echo $cat['id']; ?>"
data-tipo="<?php echo $cat['tipo_modulo']; ?>"
>

<?php echo $cat['nombre']; ?>

</option>

<?php } ?>

</select>

</div>

<div class="form-group">
<label>Título</label>
<input type="text" name="titulo" required>
</div>

<div class="form-group">
<label>Descripción</label>
<textarea name="descripcion"></textarea>
</div>

<div id="dynamicFields"></div>

<button
class="btn-save"
type="submit"
name="guardar"
>
Guardar Publicación
</button>

</form>

</div>

</div>

<script>

function openModal(){

    document
    .getElementById('publicacionModal')
    .style.display='flex';

}

function closeModal(){

    document
    .getElementById('publicacionModal')
    .style.display='none';

}

const categoriaSelect =
document.getElementById('categoriaSelect');

const dynamicFields =
document.getElementById('dynamicFields');

categoriaSelect.addEventListener('change',()=>{

const tipo =
categoriaSelect.options[
    categoriaSelect.selectedIndex
].dataset.tipo;

    dynamicFields.innerHTML='';

    /* HERRAMIENTAS */

    if(tipo=='herramientas'){

        dynamicFields.innerHTML=`

        <div class="form-group">
        <label>Link</label>
        <input type="text" name="link">
        </div>

        <div class="form-group">
        <label>Imagen</label>
        <input type="file" name="imagen">
        </div>

        `;

    }

    /* ACTIVIDADES */

    if(tipo=='actividades'){

        dynamicFields.innerHTML=`

        <div class="form-group">
        <label>Fecha inicio</label>
        <input type="date" name="fecha_inicio">
        </div>

        <div class="form-group">
        <label>Fecha fin</label>
        <input type="date" name="fecha_fin">
        </div>

        <div class="form-group">
        <label>Hora</label>
        <input type="time" name="hora">
        </div>

        `;

    }

    /* EVENTOS */

    if(tipo=='eventos'){

        dynamicFields.innerHTML=`

        <div class="form-group">
        <label>Fecha evento</label>
        <input type="date" name="fecha_inicio">
        </div>

        <div class="form-group">
        <label>Banner</label>
        <input type="file" name="imagen">
        </div>

        `;

    }

    /* GALERÍA */

    if(tipo=='galeria'){

        dynamicFields.innerHTML=`

        <div class="form-group">
        <label>Imagen</label>
        <input type="file" name="imagen">
        </div>

        `;

    }

    /* ARCHIVOS */

    if(tipo=='archivos'){

        dynamicFields.innerHTML=`

        <div class="form-group">
        <label>Archivo</label>
        <input type="file" name="archivo">
        </div>

        `;

    }

    /* TUTORIALES */

    if(tipo=='tutoriales'){

        dynamicFields.innerHTML=`

        <div class="form-group">
        <label>Video YouTube</label>
        <input type="text" name="video">
        </div>

        <div class="form-group">
        <label>Archivo PDF</label>
        <input type="file" name="archivo">
        </div>

        `;

    }

    /* ALERTAS */

    if(tipo=='alertas'){

        dynamicFields.innerHTML=`

        <div class="form-group">
        <label>Prioridad</label>

        <select name="prioridad">
        <option>Alta</option>
        <option>Media</option>
        <option>Baja</option>
        </select>

        </div>

        `;

    }

    /* REUNIONES */

    if(tipo=='reuniones'){

        dynamicFields.innerHTML=`

        <div class="form-group">
        <label>Fecha</label>
        <input type="date" name="fecha_inicio">
        </div>

        <div class="form-group">
        <label>Hora</label>
        <input type="time" name="hora">
        </div>

        `;

    }

});

</script>

<?php include '../includes/footer.php'; ?>
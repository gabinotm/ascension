<?php

include '../includes/header.php';
include '../includes/navbar.php';

/* =========================
GUARDAR ANUNCIO
========================= */

if(isset($_POST['guardar'])){

    $titulo = mysqli_real_escape_string($conn,$_POST['titulo']);

    $descripcion = mysqli_real_escape_string($conn,$_POST['descripcion']);

    $tipo = mysqli_real_escape_string($conn,$_POST['tipo']);

    $categoria_id = $_POST['categoria_id'];

    $usuario_id = $_SESSION['id'];

    $imagen = '';

    /* SUBIR IMAGEN */

    if($_FILES['imagen']['name'] != ''){

        $nombreImagen = time().'_'.$_FILES['imagen']['name'];

        $ruta = '../assets/img/anuncios/'.$nombreImagen;

        move_uploaded_file(
            $_FILES['imagen']['tmp_name'],
            $ruta
        );

        $imagen = $nombreImagen;

    }

    $query = "

    INSERT INTO anuncios
    (
    titulo,
    descripcion,
    imagen,
    tipo,
    categoria_id,
    usuario_id
    )

    VALUES
    (
    '$titulo',
    '$descripcion',
    '$imagen',
    '$tipo',
    '$categoria_id',
    '$usuario_id'
    )

    ";

    mysqli_query($conn,$query);

    header('Location: anuncios.php?success=1');
    exit;

}

/* =========================
ACTUALIZAR
========================= */

if(isset($_POST['actualizar'])){

    $id = $_POST['id'];

    $titulo = mysqli_real_escape_string($conn,$_POST['titulo']);

    $descripcion = mysqli_real_escape_string($conn,$_POST['descripcion']);

    $tipo = mysqli_real_escape_string($conn,$_POST['tipo']);

    $categoria_id = $_POST['categoria_id'];

    /* NUEVA IMAGEN */

    if($_FILES['imagen']['name'] != ''){

        $nombreImagen = time().'_'.$_FILES['imagen']['name'];

        $ruta = '../assets/img/anuncios/'.$nombreImagen;

        move_uploaded_file(
            $_FILES['imagen']['tmp_name'],
            $ruta
        );

        mysqli_query($conn,"

        UPDATE anuncios

        SET

        titulo='$titulo',
        descripcion='$descripcion',
        tipo='$tipo',
        categoria_id='$categoria_id',
        imagen='$nombreImagen'

        WHERE id='$id'

        ");

    }else{

        mysqli_query($conn,"

        UPDATE anuncios

        SET

        titulo='$titulo',
        descripcion='$descripcion',
        tipo='$tipo',
        categoria_id='$categoria_id'

        WHERE id='$id'

        ");

    }

    header('Location: anuncios.php?update=1');
    exit;

}

/* =========================
ELIMINAR
========================= */

if(isset($_GET['eliminar'])){

    $id = $_GET['eliminar'];

    mysqli_query($conn,"

    DELETE FROM anuncios

    WHERE id='$id'

    ");

    header('Location: anuncios.php?delete=1');
    exit;

}

/* =========================
CATEGORÍAS
========================= */

$queryCategorias = "

SELECT * FROM categorias

WHERE estado='Activo'

ORDER BY nombre ASC

";

$resultCategorias = mysqli_query($conn,$queryCategorias);

/* =========================
CONSULTAR ANUNCIOS
========================= */

$queryAnuncios = "

SELECT anuncios.*,

usuarios.nombres,

roles.nombre AS rol,

categorias.nombre AS categoria

FROM anuncios

LEFT JOIN usuarios
ON anuncios.usuario_id = usuarios.id

LEFT JOIN roles
ON usuarios.rol_id = roles.id

LEFT JOIN categorias
ON anuncios.categoria_id = categorias.id

ORDER BY anuncios.id DESC

";

$resultAnuncios = mysqli_query($conn,$queryAnuncios);

?>

<!-- =========================
TITLE
========================= -->

<section class="page-title">

<h2>
Anuncios
</h2>

<p>
Gestión de anuncios institucionales
</p>

</section>

<!-- =========================
BOTÓN
========================= -->

<div class="top-actions">

<button
class="btn-add"
onclick="openModal()"
>

<i class="fa-solid fa-plus"></i>

Nuevo Anuncio

</button>

</div>

<!-- =========================
ANUNCIOS
========================= -->

<section class="announcement-container">

<?php while($row = mysqli_fetch_assoc($resultAnuncios)){ ?>

<div class="announcement-card">

<!-- HEADER -->

<div class="announcement-header">

<div class="announcement-user">

<div class="announcement-avatar">

<i class="fa-solid fa-user"></i>

</div>

<div>

<h4>

<?php echo $row['nombres']; ?>

</h4>

<span>

<?php echo $row['rol']; ?>

</span>

</div>

</div>

<div class="announcement-top-right">

<span class="announcement-category">

<?php echo $row['categoria']; ?>

</span>

<span class="announcement-date">

<?php echo date(
'd/m/Y H:i',
strtotime($row['fecha_publicacion'])
); ?>

</span>

</div>

</div>

<!-- BODY -->

<div class="announcement-body">

<h3>

<?php echo $row['titulo']; ?>

</h3>

<div class="announcement-type">

<?php echo $row['tipo']; ?>

</div>

<p>

<?php echo nl2br($row['descripcion']); ?>

</p>

<?php if($row['imagen'] != ''){ ?>

<img
src="../assets/img/anuncios/<?php echo $row['imagen']; ?>"
class="announcement-image"
>

<?php } ?>

</div>

<!-- FOOTER -->

<div class="announcement-footer">

<button
class="btn-edit"

data-id="<?php echo $row['id']; ?>"

data-titulo="<?php echo htmlspecialchars($row['titulo']); ?>"

data-descripcion="<?php echo htmlspecialchars($row['descripcion']); ?>"

data-tipo="<?php echo htmlspecialchars($row['tipo']); ?>"

data-categoria="<?php echo $row['categoria_id']; ?>"

onclick="editAnnouncement(this)"

>

Editar

</button>

<button
class="btn-delete"
onclick="deleteAnnouncement(<?php echo $row['id']; ?>)"
>

Eliminar

</button>

</div>

</div>

<?php } ?>

</section>

<!-- =========================
MODAL
========================= -->

<div class="modal" id="announcementModal">

<div class="modal-content large-modal">

<div class="modal-header">

<h3 id="modalTitle">
Nuevo Anuncio
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
>

<input
type="hidden"
name="id"
id="edit_id"
>

<!-- TÍTULO -->

<div class="form-group">

<label>Título</label>

<input
type="text"
name="titulo"
id="titulo"
required
>

</div>

<!-- TIPO -->

<div class="form-group">

<label>Tipo</label>

<select
name="tipo"
id="tipo"
required
>

<option value="Comunicado">
Comunicado
</option>

<option value="Urgente">
Urgente
</option>

<option value="Evento">
Evento
</option>

<option value="Aviso">
Aviso
</option>

</select>

</div>

<!-- CATEGORÍA -->

<div class="form-group">

<label>Categoría</label>

<select
name="categoria_id"
id="categoria_id"
required
>

<option value="">
Seleccione
</option>

<?php

mysqli_data_seek($resultCategorias,0);

while($cat = mysqli_fetch_assoc($resultCategorias)){ ?>

<option value="<?php echo $cat['id']; ?>">

<?php echo $cat['nombre']; ?>

</option>

<?php } ?>

</select>

</div>

<!-- DESCRIPCIÓN -->

<div class="form-group">

<label>Descripción</label>

<textarea
name="descripcion"
id="descripcion"
rows="6"
required
></textarea>

</div>

<!-- IMAGEN -->

<div class="form-group">

<label>Imagen</label>

<input
type="file"
name="imagen"
accept="image/*"
>

</div>

<!-- BOTÓN -->

<button
type="submit"
name="guardar"
id="submitBtn"
class="btn-save"
>

Publicar

</button>

</form>

</div>

</div>

<!-- =========================
SWEET ALERT
========================= -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

/* =========================
ABRIR MODAL
========================= */

function openModal(){

    document.getElementById('announcementModal').style.display='flex';

    document.getElementById('modalTitle').innerHTML='Nuevo Anuncio';

    document.getElementById('submitBtn').name='guardar';

    document.getElementById('submitBtn').innerHTML='Publicar';

}

/* =========================
CERRAR MODAL
========================= */

function closeModal(){

    document.getElementById('announcementModal').style.display='none';

}

/* =========================
EDITAR
========================= */

function editAnnouncement(button){

    openModal();

    document.getElementById('modalTitle').innerHTML='Editar Anuncio';

    document.getElementById('edit_id').value =
    button.dataset.id;

    document.getElementById('titulo').value =
    button.dataset.titulo;

    document.getElementById('descripcion').value =
    button.dataset.descripcion;

    document.getElementById('tipo').value =
    button.dataset.tipo;

    document.getElementById('categoria_id').value =
    button.dataset.categoria;

    document.getElementById('submitBtn').name='actualizar';

    document.getElementById('submitBtn').innerHTML='Actualizar';

}

/* =========================
ELIMINAR
========================= */

function deleteAnnouncement(id){

    Swal.fire({

        title:'¿Eliminar anuncio?',

        icon:'warning',

        showCancelButton:true,

        confirmButtonColor:'#d33',

        confirmButtonText:'Eliminar'

    }).then((result)=>{

        if(result.isConfirmed){

            window.location='anuncios.php?eliminar='+id;

        }

    });

}

/* =========================
ALERTAS
========================= */

<?php if(isset($_GET['success'])){ ?>

Swal.fire({
icon:'success',
title:'Anuncio publicado'
});

<?php } ?>

<?php if(isset($_GET['update'])){ ?>

Swal.fire({
icon:'success',
title:'Anuncio actualizado'
});

<?php } ?>

<?php if(isset($_GET['delete'])){ ?>

Swal.fire({
icon:'success',
title:'Anuncio eliminado'
});

<?php } ?>

</script>

<?php include '../includes/footer.php'; ?>
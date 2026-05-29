<?php

include '../includes/header.php';
include '../includes/navbar.php';

require_once '../config/conexion.php';

/* =========================
DIRECTORIO IMÁGENES
========================= */

$directorio = "../assets/img/";

if(!is_dir($directorio)){
    mkdir($directorio,0777,true);
}

/* =========================
GUARDAR
========================= */

if(isset($_POST['guardar'])){

    $titulo = mysqli_real_escape_string($conn,$_POST['titulo']);

    $descripcion = mysqli_real_escape_string($conn,$_POST['descripcion']);

    $link = mysqli_real_escape_string($conn,$_POST['link']);

    $categoria = $_POST['categoria'];

    $subcategoria = $_POST['subcategoria'];

    /* IMAGEN */

    $imagen = $_FILES['imagen']['name'];

    $tmp = $_FILES['imagen']['tmp_name'];

    $nombreImagen = time().'_'.$imagen;

    move_uploaded_file($tmp,$directorio.$nombreImagen);

    /* INSERT */

    $query = "
    INSERT INTO herramientas
    (
    titulo,
    descripcion,
    imagen,
    link,
    categoria_id,
    subcategoria_id
    )

    VALUES
    (
    '$titulo',
    '$descripcion',
    '$nombreImagen',
    '$link',
    '$categoria',
    '$subcategoria'
    )
    ";

    mysqli_query($conn,$query);

    header("Location: herramientas.php?success=1");

}

/* =========================
ELIMINAR
========================= */

if(isset($_GET['eliminar'])){

    $id = $_GET['eliminar'];

    $buscar = mysqli_query($conn,"
    SELECT * FROM herramientas
    WHERE id='$id'
    ");

    $data = mysqli_fetch_assoc($buscar);

    if(file_exists($directorio.$data['imagen'])){

        unlink($directorio.$data['imagen']);

    }

    mysqli_query($conn,"
    DELETE FROM herramientas
    WHERE id='$id'
    ");

    header("Location: herramientas.php?delete=1");

}

/* =========================
ACTUALIZAR
========================= */

if(isset($_POST['actualizar'])){

    $id = $_POST['id'];

    $titulo = mysqli_real_escape_string($conn,$_POST['titulo']);

    $descripcion = mysqli_real_escape_string($conn,$_POST['descripcion']);

    $link = mysqli_real_escape_string($conn,$_POST['link']);

    $categoria = $_POST['categoria'];

    $subcategoria = $_POST['subcategoria'];

    /* NUEVA IMAGEN */

    if($_FILES['imagen']['name'] != ""){

        $imagen = $_FILES['imagen']['name'];

        $tmp = $_FILES['imagen']['tmp_name'];

        $nombreImagen = time().'_'.$imagen;

        move_uploaded_file($tmp,$directorio.$nombreImagen);

        /* ELIMINAR ANTIGUA */

        $buscar = mysqli_query($conn,"
        SELECT * FROM herramientas
        WHERE id='$id'
        ");

        $data = mysqli_fetch_assoc($buscar);

        if(file_exists($directorio.$data['imagen'])){

            unlink($directorio.$data['imagen']);

        }

        $query = "
        UPDATE herramientas
        SET
        titulo='$titulo',
        descripcion='$descripcion',
        imagen='$nombreImagen',
        link='$link',
        categoria_id='$categoria',
        subcategoria_id='$subcategoria'
        WHERE id='$id'
        ";

    }else{

        $query = "
        UPDATE herramientas
        SET
        titulo='$titulo',
        descripcion='$descripcion',
        link='$link',
        categoria_id='$categoria',
        subcategoria_id='$subcategoria'
        WHERE id='$id'
        ";

    }

    mysqli_query($conn,$query);

    header("Location: herramientas.php?update=1");

}

/* =========================
CATEGORÍAS
========================= */

$queryCategorias = "
SELECT * FROM categorias
ORDER BY nombre ASC
";

$resultCategorias = mysqli_query($conn,$queryCategorias);

/* =========================
HERRAMIENTAS
========================= */

$queryHerramientas = "

SELECT herramientas.*,

categorias.nombre AS categoria,

subcategorias.nombre AS subcategoria

FROM herramientas

LEFT JOIN categorias
ON herramientas.categoria_id = categorias.id

LEFT JOIN subcategorias
ON herramientas.subcategoria_id = subcategorias.id

ORDER BY herramientas.id DESC

";

$resultHerramientas = mysqli_query($conn,$queryHerramientas);

?>

<!-- =========================
TITLE
========================= -->

<section class="page-title">

<h2>Herramientas</h2>

<p>
Administración de herramientas del sistema
</p>

</section>

<!-- =========================
TOP ACTIONS
========================= -->

<div class="top-actions">

<button class="btn-add" onclick="openModal()">

<i class="fa-solid fa-plus"></i>

Nueva Herramienta

</button>

</div>

<!-- =========================
FILTROS
========================= -->

<div class="filters">

<input
type="text"
id="searchInput"
placeholder="Buscar herramienta..."
>

<select id="filterCategoria">

<option value="">
Todas las categorías
</option>

<?php

mysqli_data_seek($resultCategorias,0);

while($cat = mysqli_fetch_assoc($resultCategorias)){ ?>

<option value="<?php echo $cat['nombre']; ?>">

<?php echo $cat['nombre']; ?>

</option>

<?php } ?>

</select>

</div>

<!-- =========================
TABLA
========================= -->

<section class="admin-table">

<table>

<thead>

<tr>

<th>ID</th>
<th>Imagen</th>
<th>Título</th>
<th>Categoría</th>
<th>Subcategoría</th>
<th>Acciones</th>

</tr>

</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($resultHerramientas)){ ?>

<tr>

<td>
<?php echo $row['id']; ?>
</td>

<td>

<img
src="../assets/img/<?php echo $row['imagen']; ?>"
class="table-img"
>

</td>

<td>
<?php echo $row['titulo']; ?>
</td>

<td>
<?php echo $row['categoria']; ?>
</td>

<td>
<?php echo $row['subcategoria']; ?>
</td>

<td>

<button
class="btn-edit"
onclick='editTool(
<?php echo json_encode($row); ?>
)'
>

Editar

</button>

<button
class="btn-delete"
onclick="deleteTool(<?php echo $row['id']; ?>)"
>

Eliminar

</button>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</section>

<!-- =========================
MODAL
========================= -->

<div class="modal" id="toolModal">

<div class="modal-content">

<div class="modal-header">

<h3 id="modalTitle">
Nueva Herramienta
</h3>

<button class="close-btn" onclick="closeModal()">
×
</button>

</div>

<form
method="POST"
enctype="multipart/form-data"
id="toolForm"
>

<input type="hidden" name="id" id="id">

<div class="form-group">

<label>Título</label>

<input
type="text"
name="titulo"
id="titulo"
required
>

</div>

<div class="form-group">

<label>Descripción</label>

<textarea
name="descripcion"
id="descripcion"
required
></textarea>

</div>

<div class="form-group">

<label>Link</label>

<input
type="text"
name="link"
id="link"
required
>

</div>

<div class="form-group">

<label>Categoría</label>

<select
name="categoria"
id="categoria"
required
onchange="loadSubcategorias(this.value)"
>

<option value="">
Seleccione categoría
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

<div class="form-group">

<label>Subcategoría</label>

<select
name="subcategoria"
id="subcategoria"
required
>

<option value="">
Seleccione subcategoría
</option>

</select>

</div>

<div class="form-group">

<label>Imagen</label>

<input
type="file"
name="imagen"
id="imagen"
>

</div>

<button
type="submit"
name="guardar"
id="submitBtn"
class="btn-save"
>

Guardar

</button>

</form>

</div>

</div>

<!-- SWEETALERT -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

/* =========================
MODAL
========================= */

function openModal(){

document.getElementById('toolModal').style.display='flex';

document.getElementById('toolForm').reset();

document.getElementById('modalTitle').innerHTML='Nueva Herramienta';

document.getElementById('submitBtn').name='guardar';

}

function closeModal(){

document.getElementById('toolModal').style.display='none';

}

/* =========================
CARGAR SUBCATEGORÍAS
========================= */

function loadSubcategorias(categoria){

if(categoria == ''){

document.getElementById('subcategoria').innerHTML = `
<option value="">
Seleccione subcategoría
</option>
`;

return;

}

fetch('get_subcategorias.php?categoria='+categoria)

.then(response => response.text())

.then(data => {

document.getElementById('subcategoria').innerHTML = data;

});

}

/* =========================
EDITAR
========================= */

function editTool(data){

openModal();

document.getElementById('modalTitle').innerHTML='Editar Herramienta';

document.getElementById('id').value=data.id;

document.getElementById('titulo').value=data.titulo;

document.getElementById('descripcion').value=data.descripcion;

document.getElementById('link').value=data.link;

document.getElementById('categoria').value=data.categoria_id;

/* CARGAR SUBCATEGORÍAS */

fetch('get_subcategorias.php?categoria='+data.categoria_id)

.then(response => response.text())

.then(html => {

document.getElementById('subcategoria').innerHTML = html;

document.getElementById('subcategoria').value = data.subcategoria_id;

});

document.getElementById('submitBtn').name='actualizar';

}

/* =========================
ELIMINAR
========================= */

function deleteTool(id){

Swal.fire({

title:'¿Eliminar herramienta?',

text:'Esta acción no se puede deshacer',

icon:'warning',

showCancelButton:true,

confirmButtonColor:'#d33',

cancelButtonColor:'#3085d6',

confirmButtonText:'Sí, eliminar'

}).then((result)=>{

if(result.isConfirmed){

window.location='herramientas.php?eliminar='+id;

}

});

}

/* =========================
FILTROS
========================= */

const searchInput = document.getElementById('searchInput');

const filterCategoria = document.getElementById('filterCategoria');

function filterTable(){

const text = searchInput.value.toLowerCase().trim();

const categoria = filterCategoria.value.toLowerCase().trim();

const rows = document.querySelectorAll('tbody tr');

rows.forEach(row => {

const contenido = row.innerText.toLowerCase().trim();

const categoriaRow = row.children[3].innerText.toLowerCase().trim();

const matchText = contenido.includes(text);

const matchCategoria =
categoria === "" ||
categoriaRow.includes(categoria);

if(matchText && matchCategoria){

row.style.display = '';

}else{

row.style.display = 'none';

}

});

}

searchInput.addEventListener('keyup', filterTable);

filterCategoria.addEventListener('change', filterTable);

/* =========================
ALERTAS
========================= */

<?php if(isset($_GET['success'])){ ?>

Swal.fire({
icon:'success',
title:'Herramienta guardada'
});

<?php } ?>

<?php if(isset($_GET['update'])){ ?>

Swal.fire({
icon:'success',
title:'Herramienta actualizada'
});

<?php } ?>

<?php if(isset($_GET['delete'])){ ?>

Swal.fire({
icon:'success',
title:'Herramienta eliminada'
});

<?php } ?>

</script>

<?php include '../includes/footer.php'; ?>
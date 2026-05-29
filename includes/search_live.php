<?php

require_once '../config/conexion.php';
require_once '../config/app.php';

/* =========================================================
UTF8
========================================================= */

mysqli_set_charset(
$conn,
'utf8mb4'
);

/* =========================================================
BUSQUEDA
========================================================= */

$buscar = trim($_GET['q'] ?? '');

if($buscar == ''){

exit;

}

$buscar = mysqli_real_escape_string(
$conn,
$buscar
);

/* =========================================================
QUERY
========================================================= */

$query = "

SELECT

publicaciones.id,
publicaciones.titulo,
publicaciones.descripcion,
publicaciones.imagen,
publicaciones.tipo_publicacion,

subcategorias.id AS subcategoria

FROM publicaciones

LEFT JOIN subcategorias
ON publicaciones.subcategoria_id = subcategorias.id

WHERE

publicaciones.titulo LIKE '%$buscar%'

OR

publicaciones.descripcion LIKE '%$buscar%'

OR

publicaciones.tipo_publicacion LIKE '%$buscar%'

ORDER BY publicaciones.id DESC

LIMIT 8

";

$resultado = mysqli_query(
$conn,
$query
);

/* =========================================================
SIN RESULTADOS
========================================================= */

if(mysqli_num_rows($resultado) == 0){

?>

<div class="search-empty">

No se encontraron resultados

</div>

<?php

exit;

}

/* =========================================================
RESULTADOS
========================================================= */

while($row = mysqli_fetch_assoc($resultado)){

/* IMAGEN */

$imagen =
BASE_URL.'img/default.webp';

if($row['imagen'] != ''){

$imagen =
BASE_URL.'img/'.$row['imagen'];

}

?>

<a
href="<?php echo BASE_URL; ?>modulo.php?subcategoria=<?php echo $row['subcategoria']; ?>"
class="search-result-item"
>

<img
src="<?php echo $imagen; ?>"
loading="lazy"
>

<div class="search-result-info">

<h6>

<?php echo $row['titulo']; ?>

</h6>

<span>

<?php echo ucfirst($row['tipo_publicacion']); ?>

</span>

</div>

</a>

<?php } ?>
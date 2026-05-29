<?php

require_once '../config/conexion.php';

$categoria = $_GET['categoria'];

$query = "
SELECT * FROM subcategorias
WHERE categoria_id='$categoria'
ORDER BY nombre ASC
";

$result = mysqli_query($conn,$query);

while($row = mysqli_fetch_assoc($result)){

?>

<option value="<?php echo $row['id']; ?>">

<?php echo $row['nombre']; ?>

</option>

<?php } ?>
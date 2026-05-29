<?php
session_start();

include 'config/conexion.php';

if(isset($_SESSION['usuario'])){

header("Location: dashboard.php");
exit();

}

$error = "";

if(isset($_POST['login'])){

$usuario =
mysqli_real_escape_string(
$conn,
$_POST['usuario']
);

$password = $_POST['password'];

$query = "

SELECT *

FROM usuarios

WHERE usuario='$usuario'

LIMIT 1

";

$resultado =
mysqli_query($conn,$query);

if(mysqli_num_rows($resultado) > 0){

$fila =
mysqli_fetch_assoc($resultado);

if(password_verify($password,$fila['password'])){

$_SESSION['id'] = $fila['id'];

$_SESSION['usuario'] = $fila['usuario'];

$_SESSION['nombres'] = $fila['nombres'];

$_SESSION['rol_id'] = $fila['rol_id'];
if(

$fila['rol_id'] == 1

||

$fila['rol_id'] == 2

||

$fila['rol_id'] == 4

){

header("Location: dashboard.php");

}else{

header("Location: index.php");

}

exit();

header("Location: dashboard.php");
exit();

}else{

$error = "Contraseña incorrecta";

}

}else{

$error = "Usuario no encontrado";

}

}
?>

<!DOCTYPE html>

<html lang="es">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0"
>

<title>

Login

</title>

<link
rel="stylesheet"
href="assets/css/styles.css"
>

<link
href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
rel="stylesheet"
>

</head>

<body class="login-body">

<div class="login-container">

<div class="login-box">

<!-- LOGO -->

<img
src="assets/img/logo2.png"
class="login-logo"
>

<!-- TITULO -->

<h2>

Portal
Institucional

</h2>

<p>

Iniciar sesión en el sistema

</p>

<!-- ERROR -->

<?php if($error != ""){ ?>

<div class="alert-error">

<?php echo $error; ?>

</div>

<?php } ?>

<!-- FORM -->

<form method="POST">

<!-- USUARIO -->

<label>

Usuario

</label>

<div class="input-group">

<input
type="text"
name="usuario"
placeholder="Ingrese usuario"
required
>

</div>

<!-- PASSWORD -->

<label>

Contraseña

</label>

<div class="input-group">

<input
type="password"
name="password"
placeholder="Ingrese contraseña"
required
>

</div>

<!-- BTN -->

<button
type="submit"
name="login"
>

Ingresar al Sistema

</button>

</form>

<!-- FOOTER -->

<div class="login-footer">

Portal Educativo Institucional

</div>

</div>

</div>

</body>

</html>
<?php

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

require_once __DIR__.'/../config/app.php';

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
Sistema Institucional
</title>

<!-- GOOGLE FONT -->

<link
rel="preconnect"
href="https://fonts.googleapis.com"
>

<link
rel="preconnect"
href="https://fonts.gstatic.com"
crossorigin
>

<link
href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
rel="stylesheet"
>

<!-- BOOTSTRAP CSS -->

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet"
>

<!-- BOOTSTRAP ICONS -->

<link
rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
>

<!-- SWEET ALERT -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- CSS GLOBAL -->

<link
rel="stylesheet"
href="<?php echo BASE_URL; ?>assets/css/styles.css?v=<?php echo time(); ?>"
>

<link
rel="stylesheet"
href="<?php echo BASE_URL; ?>assets/css/home.css?v=<?php echo time(); ?>"
>
<!-- Nav bar-->
<link
rel="stylesheet"
href="<?php echo BASE_URL; ?>assets/css/navbar.css"
>
<!-- FAVICON -->

<link
rel="icon"
type="image/png"
href="<?php echo BASE_URL; ?>img/favicon.png"
>
<link
rel="prefetch"
href="modulo.php?subcategoria=1"
>

<link
rel="prefetch"
href="dashboard.php"
>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
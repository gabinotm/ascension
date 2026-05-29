<?php

if(session_status() === PHP_SESSION_NONE){
session_start();
}

/* =========================================================
VALIDAR LOGIN
========================================================= */

if(!isset($_SESSION['id'])){

require_once __DIR__.'/../config/app.php';

header('Location: '.BASE_URL.'login.php');
exit;

}

/* =========================================================
ROLES
========================================================= */

function esAdministrador(){

return
isset($_SESSION['rol_id'])
&&
$_SESSION['rol_id'] == 1;

}

function esDirector(){

return
isset($_SESSION['rol_id'])
&&
$_SESSION['rol_id'] == 2;

}

function esDocente(){

return
isset($_SESSION['rol_id'])
&&
$_SESSION['rol_id'] == 3;

}

function esTice(){

return
isset($_SESSION['rol_id'])
&&
$_SESSION['rol_id'] == 4;

}

function accesoTotal(){

return
esAdministrador()
||
esTice();

}
<?php

$host = "localhost";
$user = "root";
$pass = "";
$db = "colegio-ascension";

/** @var mysqli $conn */
$conn = mysqli_connect($host,$user,$pass,$db);

mysqli_set_charset($conn,"utf8");

if(!$conn){
    die("Error de conexión");
}

?>
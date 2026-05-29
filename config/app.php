<?php

$host = $_SERVER['HTTP_HOST'];

if(
strpos($host,'localhost') !== false
){

define(
'BASE_URL',
'http://localhost/colegio-ascension/'
);

}else{

define(
'BASE_URL',
'https://sisappweb.com/colegio-ascension/'
);

}

?>
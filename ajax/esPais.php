<?php
require_once '../funciones/constantes.php';
require_once '../clases/BD.php';
require_once '../clases/Pais.php';
session_start();

if($_SERVER["HTTP_REFERER"] != $GLOBALS["ruta"]){
    die();
}

$bd = new BD();
if(Pais::esPais($_GET["pais"], $bd)){
    echo 1;
}else{
    echo 0;
}
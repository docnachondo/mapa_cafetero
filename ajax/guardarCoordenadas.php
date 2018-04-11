<?php
require_once '../funciones/constantes.php';
require_once '../clases/BD.php';
require_once '../clases/Oyente.php';
session_start();

if($_SERVER["HTTP_REFERER"] != $GLOBALS["ruta"]){
    die();
}

$idOyente = $_GET["oyente"];
$coordenada = explode(",", substr($_GET["coordenada"], 1, strlen($_GET["coordenada"])-2));

$bd = new BD();
if(Oyente::cambiarCoordenada($idOyente, $coordenada, $bd)){
    $oyente = $_SESSION["usuario"];
    $oyente->setLat($coordenada[0]);
    $oyente->setLon($coordenada[1]);
    $_SESSION["usuario"] = $oyente;
    echo 1;
}
echo 0;
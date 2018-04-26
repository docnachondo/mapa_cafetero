<?php
require_once '../clases/BD.php';
require_once '../clases/Oyente.php';

session_start();

if(isset($_SESSION["usuario"]) && $_SESSION["usuario"]->getAdmin()){
    $bd = new BD();
    $listaOyentes = Oyente::todosActivosMarcados($bd);

    echo json_encode($listaOyentes);
}
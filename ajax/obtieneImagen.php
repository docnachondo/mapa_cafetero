<?php
require_once '../funciones/constantes.php';
require_once '../funciones/log.php';
require_once '../clases/BD.php';
require_once '../clases/Oyente.php';
require_once '../clases/Interes.php';
session_start();

if($_SERVER["HTTP_REFERER"] !=  $GLOBALS["ruta"]){
    die();
}

$cuenta = $_REQUEST["cuenta"];
$idOyente = $_REQUEST["oyente"];
$cur = curl_init();

curl_setopt($cur, CURLOPT_HEADER, TRUE);
curl_setopt($cur, CURLOPT_NOBODY, TRUE);
curl_setopt($cur, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($cur, CURLOPT_URL, "https://twitter.com/".$cuenta."/profile_image?size=bigger");

$head = curl_exec($cur);
$imagen = explode("\n", substr($head, strpos($head, 'location:')+10))[0];

curl_setopt($cur, CURLOPT_HEADER, FALSE);
curl_setopt($cur, CURLINFO_HEADER_OUT, TRUE);
curl_setopt($cur, CURLOPT_NOBODY, FALSE);
curl_setopt($cur, CURLOPT_URL, "https://twitter.com/".$cuenta);
$cuerpo = curl_exec($cur);

$resultados = array();
preg_match("/<title>.+ \(/", $cuerpo, $resultados);

$nombre = str_replace(" (","",  str_replace("<title>", "", $resultados[0]));

curl_close($cur);

$textoAdicional = "nn";

if(isset($_SESSION["usuario"]) && $_SESSION["usuario"]->getAdmin()){
    $bd = new BD();
    $elOyente = Oyente::oyentePorId($idOyente, $bd);

    $textoAdicional = '<div class="info_exta"><div class="nombre"><span>Nombre:</span><span>'.$elOyente->getNombre().'</span></div>';
    $textoAdicional.= '<div class="apellido"><span>Apellido:</span><span>'.$elOyente->getApellido().'</span></div>';
    $textoAdicional.= '<div class="telefono"><span>Telefono:</span><span>'.$elOyente->getTelefono().'</span></div>';
    $textoAdicional.= '<div class="email"><span>Email:</span><span><a href="mailto:'.$elOyente->getEmail().'?Subject=Mensaje%20cafetero">'.$elOyente->getEmail().'</a></span></div>';
    $textoAdicional.= '<div class="fecha_nacimiento"><span>Nacimiento:</span><span>'.str_replace(" 00:00:00", "", $elOyente->getFechaNacimiento()).'</span></div>';

    $inter = Interes::listarOyente($idOyente, $bd);
    if(count($inter) > 0){
        $textoAdicional.= '<div class="intereses">';
        foreach ($inter as $inte){
            $textoAdicional.= '<img src="images/'.$inte->getFoto().'" title="'.$inte->getNombre().'"/>';
        }
        $textoAdicional.= '</div>';
    }
    $textoAdicional.= '</div>';
}

echo $imagen."|".$nombre."|".$textoAdicional;
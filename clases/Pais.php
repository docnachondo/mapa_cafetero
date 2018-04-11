<?php
require_once 'BD.php';
/**
 * Description of pais
 *
 * @author Nacho Escursell
 */
class Pais {
    private $nombre;
    private $idPais;
    
    function __construct($nombre, $idPais) {
        $this->nombre = $nombre;
        $this->idPais = $idPais;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getIdPais() {
        return $this->idPais;
    }

    public static function listar($bd){
        $bd->setConsulta("select * from paises");
        $arr = array();
        $bd->ejecutar();
        while($it = $bd->resultado()){
            $pais = new Pais($it["nombre"], $it["id_pais"]);
            array_push($arr, $pais);
        }
        return $arr;
    }

}
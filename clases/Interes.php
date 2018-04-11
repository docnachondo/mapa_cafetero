<? require_once 'BD.php';

/**
 * Description of Interes
 *
 * @author Nacho Escursell
 */
class Interes {

    private $idInteres;
    private $nombre;
    private $foto;

    function __construct($idInteres, $nombre, $foto) {
        $this->idInteres = $idInteres;
        $this->nombre = $nombre;
        $this->foto = $foto;
    }

    function getIdInteres() {
        return $this->idInteres;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getFoto() {
        return $this->foto;
    }

    public static function listar($bd){
        $bd->setConsulta("select * from intereses");
        $arr = array();
        $bd->ejecutar();
        while($it = $bd->resultado()){
            $interes = new Interes($it["id_interes"], $it["nombre"], $it["foto"]);
            array_push($arr, $interes);
        }
        return $arr;
    }

    public static function listarOyente($idOyente, $bd){
        $bd->setConsulta("select i.nombre, i.foto from inte_oyen ino inner join intereses i on i.id_interes = ino.id_interes where ino.id_oyente = ?");
        $arr = array();
        $bd->ejecutar($idOyente);
        while($it = $bd->resultado()){
            $interes = new Interes(null, $it["nombre"], $it["foto"]);
            array_push($arr, $interes);
        }
        return $arr;
    }

}
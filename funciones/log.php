<?

function escribeLog($texto, $imprime = false){
    
    date_default_timezone_set('Europe/Madrid');
    
    $archivo = fopen(__DIR__."/logs/".date("Ymd").".log", "a");
    
    $fecha = date("Y/m/d - H:i:s");
    $usuario = "Anónimo";
    
    if(isset($_SESSION["usuario"])){
        $usuario = $_SESSION["usuario"]->getEmail();
    }

    if($imprime){
        $texto = $fecha." -\t".$usuario." -\t".$_SERVER["REMOTE_ADDR"]."-\t".var_export($texto, true);
    }else{
        $texto = $fecha." -\t".$usuario." -\t".$_SERVER["REMOTE_ADDR"]."-\t".$texto;
    }

    fwrite($archivo, $texto."\n");
}

function primeraLinea() {
    $direccion = $_SERVER["REQUEST_URI"];
    $contenido = null;
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (strpos($_SERVER["REQUEST_URI"], "?") != false) {
            $direccion = substr($_SERVER["REQUEST_URI"], 0, strpos($_SERVER["REQUEST_URI"], "?"));
        }
        $contenido = "?".$_SERVER["QUERY_STRING"];
    } else {
        foreach ($_POST as $clave => $valor) {
            if (isset($contenido)) {
                $contenido .= "&" . $clave . "=" . $valor;
            } else {
                $contenido = "?" . $clave . "=" . $valor;
            }
        }
    }

    if(!empty($contenido)){
        escribeLog("Entrando en " . $direccion . " -> " . $contenido . "[" . $_SERVER["REQUEST_METHOD"] . "]");
    }else{
        escribeLog("Entrando en " . $direccion . "[" . $_SERVER["REQUEST_METHOD"] . "]");
    }

}


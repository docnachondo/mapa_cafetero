<?php

class BD {

    private $instancia;
    private $conexion;

    public function __construct() {
        $this->conexion = new PDO('mysql:host=mysql.hostinger.es;dbname=u382194078_datos;charset=utf8', 'u382194078_admin', 'cGlGTN60xVBL');
    }

    public function setConsulta($sql) {
        $this->instancia = $this->conexion->prepare($sql);
    }

    public function ejecutar($parametros = null) {
        if(isset($parametros)){
            settype($parametros, "array");
        }
        try{
            $this->instancia->execute($parametros);
            if(strpos($this->mensaje(),"00000")=== false){
                $this->escribeRaw($this->mensaje());
            }
        }catch (PDOException $e){
            echo "<h4>error</h4><h3>".$e->getMessage()."</h3>";
        }
    }

    public function filasModificadas() {
        return $this->instancia->rowCount();
    }

    public function resultado() {
        try {
            if ($rs = $this->instancia->fetch(PDO::FETCH_ASSOC)) {
                
                return $rs;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo "<h4>error</h4><h3>" . $e->getMessage() . "</h3>";
        }
    }
    
    public function mensaje(){
        $cadena = "";
        foreach($this->instancia->errorInfo() as $err){
            $cadena.="$err -\t";
        }
        
        if(strpos($cadena, "HY093")===0){
            $cadena = "HY093 -\tEl número de parámetros no es correcto-\t";
        }
        $prueba = $this->instancia->errorInfo();
        if(empty($prueba[0])){
            return "00000 -\t-\t";
        }
        
        return $cadena;
    }
    
    public function escribeRaw($texto){
        date_default_timezone_set('Europe/Madrid');

        $archivo = fopen(__DIR__."/logs/".date("Ymd").".log", "a");
        $fecha = date("Y/m/d - H:i:s");
        $usuario = "Anónimo";

        if(isset($_SESSION["usuario"])){
            $usuario = $_SESSION["usuario"]->getEmail();
        }
        $texto = $fecha." -\t".$_SERVER["REMOTE_ADDR"]." -\t".$usuario." -\t".$texto."\n".print_r(debug_backtrace(), TRUE)."\n";

        fwrite($archivo, $texto."\n");
    }

}
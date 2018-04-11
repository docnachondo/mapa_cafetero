<?php

require_once 'PHPMailer-master/PHPMailerAutoload.php';

class Correo {

    private $adjuntos;
    private $plantilla;
    private $remplazos;

    function __construct() {
        $this->mail = new PHPMailer();
        $this->mail->isSMTP();
        //$this->mail->SMTPDebug = 1;
        $this->mail->Debugoutput = 'html';
        $this->mail->SMTPSecure = 'tls';
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->Port = 587;
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'docnachondo@gmail.com';
        $this->mail->Password = 'mrnactoo2095700';
        $this->mail->setFrom('docnachondo@gmail.com', iconv('UTF-8', 'ASCII//TRANSLIT','La Cafetera (Desarrollo)'));
        $this->remplazos = array();
        $this->adjuntos = array();
    }

    public function setAsunto($asunto) {
        $this->mail->Subject = utf8_encode($asunto);
        $this->agregarRemplazo("asunto", $asunto);
    }

    public function setPlantilla($plantilla) {
        $this->plantilla = $plantilla;
    }

    public function agregarDestinatarios($direccion, $nombre = null) {
        //Debería ir el nombre en el parámetro 2
        if(!isset($nombre)){
            $nombre = $direccion;
        }
        $this->mail->addAddress($direccion, $nombre);
    }

    public function agregarAdjunto($nombreArchivo) {
        $archivo = new Archivo($nombreArchivo);
        array_push($this->adjuntos, $archivo);
    }

    public function agregarRemplazo($nombre, $valor) {
        $total = "@" . $nombre . "@" . "|" . mb_ereg_replace("\n", "<br/>", $valor);
        array_push($this->remplazos, $total);
    }

    private function cambiar($texto) {

        foreach ($this->remplazos as $modifica) {
            $pareja = explode("|", $modifica);
            $texto = str_replace($pareja[0], $pareja[1], $texto);
        }

        return $texto;
    }

    public function enviar() {

        $fichero_base = fopen(__DIR__ . "/" . $this->plantilla.'.html', "r");
        $resultado_lectura = utf8_decode(fread($fichero_base, filesize(__DIR__ . "/" . $this->plantilla.'.html')));

        $contenidoHTML = $this->cambiar($resultado_lectura);

        $fichero_base = fopen(__DIR__ . "/" . $this->plantilla.'.txt', "r");
        $resultado_lectura = utf8_decode(fread($fichero_base, filesize(__DIR__ . "/" . $this->plantilla.'.txt')));

        $contenidoTexto = $this->cambiar($resultado_lectura);

        $this->mail->Body = $contenidoHTML;
        $this->mail->AltBody = $contenidoTexto;
        if(count($this->adjuntos) > 0){
            foreach($this->adjuntos as $adjunto){
                $this->mail->AddAttachment($adjunto->getNombre());
            }
        }
        return $this->mail->send();
    }

}

class Archivo {

    private $nombre;

    function __construct($nombre) {
        $this->nombre = $nombre;
    }

    public function getNombre() {
        return $this->nombre;
    }

}
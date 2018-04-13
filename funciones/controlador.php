<?
require_once 'constantes.php';
require_once '../clases/Oyente.php';
require_once '../clases/BD.php';
require_once 'log.php';

session_start();

primeraLinea();

$salir = 0;

if (isset($_POST["accion"])) {
    switch ($_POST["accion"]) {
        case "alta_usuario":
            altaUsuario();
            break;

        case "login":
            login();
            break;

        default :
            $_SESSION["aviso"] = "No hay accion";
            header("Location: " . $_SERVER["HTTP_REFERER"]);
            die();
            break;
    }
}else{
    $salir++;
}

if (isset($_GET["accion"])) {
    switch ($_GET["accion"]) {
        case "confirma_alta":
            confirmaAlta();
            break;

        case "desconectar":
            desconectar();
            break;

        default :
            $_SESSION["aviso"] = "No hay accion";
            header("Location: " . $_SERVER["HTTP_REFERER"]);
            die();
            break;
    }
}else{
    $salir++;
}

if($salir == 2){
    header("Location: ../");
    die();
}

function altaUsuario(){
    $error = "";

    $fechaNacimiento = "STR_TO_DATE('".$_POST["anyo"]."-".$_POST["mes"]."-".$_POST["dia"]."','%Y-%m-%d')";
    
    $oyente = new Oyente(null, trim($_POST["twitter"]), trim($_POST["pais"]), null, null, trim($_POST["nombre"]), trim($_POST["apellido"]),
            trim($_POST["email"]), false, $fechaNacimiento, trim($_POST["telefono"]), trim($_POST["clave"]), null);
    $bd = new BD();

    $headers = get_headers("https://twitter.com/".$oyente->getTwitter());
    if(strpos($headers[0], '404') !== false ) {
        $error.="La cuenta de twitter ".$oyente->getTwitter()." no existe<br/>";
    }

    if(Oyente::existeTwitter($oyente->getTwitter(), $bd)){
        $error.="Ya hay un oyente registrado con la cuenta ".$oyente->getTwitter()."<br/>";
    }

    if(Oyente::existeEmail($oyente->getEmail(), $bd)){
        $error.="Ya hay un oyente registrado con el email ".$oyente->getEmail()."<br/>";
    }

    if(strlen($error) > 0){
        $_SESSION["mensaje"] = $error;
        $_SESSION["temp"] = $oyente;
        header("Location: " . $_SERVER["HTTP_REFERER"]);
        die();
    }else{
        if($oyente->altaBd($bd)){
            $_SESSION["mensaje"] = "Bienvenido a la cafetera<br/>En breves instantes, recibiras un correo de confirmación.";
            $intereses = array();
            foreach ($_POST as $var => $clave) {
                if(substr($var, 0, 4) == "int_"){
                    array_push($intereses, substr($var, 4));
                }
            }
            Oyente::asociarInteres($oyente->getEmail(), $intereses, $bd);
            require_once '../clases/Correo.php';
            $correo = new Correo();
            $correo->setAsunto("Bienvenido al mapa cafetero. Confirma tu correo.");
            $correo->setPlantilla("plantilla_confirmacion");
            $correo->agregarDestinatarios($oyente->getEmail());
            $correo->agregarRemplazo("nombre", $oyente->getNombre());
            $correo->agregarRemplazo("clave", sha1($oyente->getClave().$oyente->getEmail()."pepito"));
            $correo->agregarRemplazo("enlace", $GLOBALS["ruta"]."funciones/controlador.php");
            $correo->agregarRemplazo("email", $oyente->getEmail());
            $correo->enviar();
            $_SESSION["usuario"] = $oyente;
            header("Location: ../");
            die();
        }else{
            $_SESSION["mensaje"] = "Hubo un error en la base de datos<br/>Vuelve a intentarlo pasados unos instantes<br/>Si el error persiste, ponte en contacto con nosortos";
            header("Location: " . $_SERVER["HTTP_REFERER"]);
            die();
        }
    }

}

function confirmaAlta(){
    $email = $_GET["email"];
    $clave = $_GET["clave"];
    
    $bd = new BD();
    $oyente = Oyente::confirmarUsuario($clave, $email, $bd);
    
    if(isset($oyente)){
        $_SESSION["usuario"] = $oyente;
        $_SESSION["mensaje"] = "La activación fue un éxito. Ya puedes localizarte en el mapa";
    }else{
        $_SESSION["mensaje"] = "La activación no es correcta";
    }
    header("Location: ../");
    die();
}

function login(){
    $email = $_POST["email"];
    $clave = $_POST["clave"];

    $bd = new BD();
    $oyente = Oyente::login($clave, $email, $bd);

    if(isset($oyente)){
        $_SESSION["usuario"] = $oyente;
        $_SESSION["mensaje"] = "Bienvenid@ ".$oyente->getNombre();
    }else{
        $_SESSION["mensaje"] = "email o clave incorrecta";
    }
    header("Location: ../");
    die();
}

function desconectar(){
    session_destroy();
    header("Location: ../");
    die();
}
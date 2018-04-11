<? require_once 'funciones/sesion.php';
$usuario;
if(isset($_SESSION["usuario"])){
    $usuario = new Oyente(null, null, null, null, null, null, null, null, null, null, null, null, null, null);
    $usuario = $_SESSION["usuario"];
}else{
    $usuario = null;
} ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Mapa Cafetero</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBiTV87XTOs3FTrWdw4bN0VlPcYmxhvV4I&libraries=places"></script>
        <script type="text/javascript" src="//scribblemaps.com/api/js/"></script>
        <script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
        <? $muesta = false;
        if(isset($usuario)){
            if($usuario->getActivo() != null){
                $muesta = true;
            }
        }
        if($muesta){ ?><script type="text/javascript" src="js/busca.js?ver=1.026"></script>
        <? }else{ ?><script type="text/javascript" src="js/muestraMapa.js?ver=1.000"></script>
        <? } ?><link type="text/css" rel="stylesheet" href="css/estilo.css?ver=1.013"/>
        <script type="text/javascript" src="js/general.js?ver=1.00"></script>
    </head>
    <body>
        <? if(isset($_SESSION["mensaje"])) {?>
            <div class="centro" style="opacity: 1;">
                <div class="Interior grande">
                    <div class="cierre" onclick="quitarCarga()">X</div>
                    <div class="caja"><?=$_SESSION["mensaje"]?></div>
                </div>
            </div>
        <? $_SESSION["mensaje"] = null;
        } ?>
        <div id="el_mapa"></div>
        <? if(isset($usuario)){
            if($usuario->getActivo() != null){
                ?>
                <a href="funciones/controlador.php?accion=desconectar">SALIR</a>
                <div class="agregar_elementos">
                    <input type="hidden" id="twitter" value="<?=$usuario->getTwitter()?>"/>
                    <input type="hidden" id="id_oyente" value="<?=$usuario->getIdOyente()?>"/>
                    <span>Direccion:</span> <input type="text" id="buscador" onkeyup="buscarSugerencias();"/>
                    <div id="sugerencias"></div>
                    <div class="clear"></div>
                    <input type="button" id="buscar" value="BUSCAR" onclick="buscarPunto();"/>
                    <div class="clear"></div>
                    <? if($usuario->getLat() == null) { ?>
                        <input type="button" id="buscar" value="AGREGAR" onclick="agregarElemento();"/>
                    <? }else{ ?>
                        <input type="button" id="buscar" value="MODIFICAR MI POSICIÓN" onclick="modificarElemento();"/>
                    <? } ?>
                </div>
            <? }else{ ?>
                <div class="agregar_elementos">
                    <span>A la espera de recibir la confirmación</span>
                </div>
            <? } ?>
        <? }else{ ?>
            <a href="registro.php">REGISTRATE</a>
            <form class="registro" method="post" action="funciones/controlador.php">
                <input type="hidden" name="accion" value="login"/>
                <span>Email: </span> <input type="text" name="email"/>
                <div class="clear"></div>
                <span>Clave: </span> <input type="password" name="clave"/>
                <div class="clear"></div>
                <input type="submit" value="Entrar"/>
            </form>
        <? } ?>
    </body>
</html>

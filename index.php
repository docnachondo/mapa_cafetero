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
        if($muesta){ ?><script type="text/javascript" src="js/busca.js?ver=<?=$GLOBALS["version"]?>"></script>
        <? }else{ ?><script type="text/javascript" src="js/muestraMapa.js?ver=<?=$GLOBALS["version"]?>"></script>
        <? } ?>
        <link type="text/css" rel="stylesheet" href="css/estilo.css?ver=<?=$GLOBALS["version"]?>"/>
        <script type="text/javascript" src="js/general.js?ver=<?=$GLOBALS["version"]?>"></script>
    </head>
    <body class="fondo">
      <div class="contenido">
        <? if(isset($_SESSION["mensaje"])) {?>
            <div id="mensaje_sistema" class="flotante">
                <a class="cerrarVentana" href="javascript:cerrar('#mensaje_sistema');">x</a>
                <?=$_SESSION["mensaje"]?>
            </div>
        <? $_SESSION["mensaje"] = null;
        } ?>
        <?php include("header.php"); ?>
        <div id="el_mapa"></div>
        <? if(isset($usuario)){
            if($usuario->getActivo() != null){
                ?>
                <div id="ventana2" class="flotante" style="display: none;">
                  <div class="formulario">
                    <input type="hidden" id="twitter" value="<?=$usuario->getTwitter()?>"/>
                    <input type="hidden" id="id_oyente" value="<?=$usuario->getIdOyente()?>"/>
                    <input type="text" id="buscador" placeholder="escribe una dirección" onkeyup="buscarSugerencias();"/>
                    <div id="sugerencias"></div>
                    <input type="button" id="buscar" class="boton" value="BUSCAR" onclick="buscarPunto(); buscarPunto();"/>
                    <? if($usuario->getLat() == null) { ?>
                        <input type="button" id="buscar" class="boton" value="AGREGAR" onclick="agregarElemento(false);"/>
                    <? }else{ ?>
                        <input type="button" id="buscar" class="boton" value="MODIFICAR MI POSICIÓN" onclick="modificarElemento();"/>
                    <? } ?>
                    <a class="cerrarVentana" href="javascript:cerrar('#ventana2');">x</a>
                  </div>
                </div>
            <? }else{ ?>
                <div class="agregar_elementos">
                    <span>A la espera de recibir la confirmación</span>
                </div>
            <? } ?>
        <? }else{ ?>
            <div id="ventana2" class="flotante" style="display: none;">
                <div class="formulario">
                    <input type="text" id="buscador" placeholder="escribe una dirección" onkeyup="buscarSugerencias();"/>
                    <div id="sugerencias"></div>
                    <input type="button" id="buscar" class="boton" value="BUSCAR" onclick="buscarPunto();buscarPunto();"/>
                    <a class="cerrarVentana" href="javascript:cerrar('#ventana2');">x</a>
                </div>
            </div>
            <div id="ventana" class="flotante">
              <form class="formulario" method="post" action="funciones/controlador.php">
                <input type="hidden" name="accion" value="login"/>
                <input type="text" name="email" placeholder="tu email"/>
                <input type="password" name="clave" placeholder="tu contraseña"/>
                <input type="submit" value="Entrar" class="boton"/>
                <p class="nueva">¿no tienes una cuenta? <a href="registro.php">crea una</a></p>
                <a class="cerrarVentana" href="javascript:cerrar('#ventana');">x</a>
              </form>
            </div>
        <? } ?>
      </div>
    </body>
</html>

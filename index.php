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
        if($muesta){ ?><script type="text/javascript" src="js/busca.js?ver=1.028"></script>
        <? }else{ ?><script type="text/javascript" src="js/muestraMapa.js?ver=1.002"></script>
        <? } ?><link type="text/css" rel="stylesheet" href="css/estilo.css?ver=1.014"/>
        <script type="text/javascript" src="js/general.js?ver=1.03"></script>
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

        <div id="cabecera">
          <div id="logo"><img src="./images/LogoCafetera.png"></div>
          <div id="titular">Mapa de la Resistencia</div>
          <div id="menu">
            <ul>
              <? if(isset($usuario)){
                  if($usuario->getActivo() != null){
                      ?>
              <li><a href="javascript:mostrar('#ventana');">Buscar</a></li>
              <li><a href="funciones/controlador.php?accion=desconectar">Salir</a></li>
              <? } ?>
              <? }else{ ?>
              <li><a href="javascript:mostrar('#ventana');">Entrar</a></li>
            <? } ?>
            </ul>
          </div>
        </div>

        <div id="el_mapa"></div>

        <? if(isset($usuario)){
            if($usuario->getActivo() != null){
                ?>
                <div id="ventana" class="flotante">
                  <div class="formulario">
                    <input type="hidden" id="twitter" value="<?=$usuario->getTwitter()?>"/>
                    <input type="hidden" id="id_oyente" value="<?=$usuario->getIdOyente()?>"/>
                    <input type="text" id="buscador" placeholder="escribe una dirección" onkeyup="buscarSugerencias();"/>
                    <div id="sugerencias"></div>
                    <input type="button" id="buscar" class="boton" value="BUSCAR" onclick="buscarPunto();"/>
                    <? if($usuario->getLat() == null) { ?>
                        <input type="button" id="buscar" class="boton" value="AGREGAR" onclick="agregarElemento();"/>
                    <? }else{ ?>
                        <input type="button" id="buscar" class="boton" value="MODIFICAR MI POSICIÓN" onclick="modificarElemento();"/>
                    <? } ?>
                    <a class="cerrarVentana" href="javascript:cerrar('#ventana');">x</a>
                  </div>
                </div>
            <? }else{ ?>
                <div class="agregar_elementos">
                    <span>A la espera de recibir la confirmación</span>
                </div>
            <? } ?>
        <? }else{ ?>
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

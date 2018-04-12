<div id="cabecera">
  <div id="logo"><img src="./images/LogoCafetera.png"></div>
  <div id="titular">Mapa de la Resistencia</div>
  <div id="menu">
    <ul>
        <? if(isset($usuario)){
            if($usuario->getActivo() != null){ ?>
                <li><a href="javascript:mostrar('#ventana');">Buscar</a></li>
                <li><a href="funciones/controlador.php?accion=desconectar">Salir</a></li>
            <? } ?>
        <? }else{
            if(!$_SERVER['PHP_SELF'] == "/registro.php"){ ?>
                <li><a href="javascript:mostrar('#ventana2');">Buscar</a></li>
            <? } ?>
        <li><a href="javascript:mostrar('#ventana');">Entrar</a></li>
        <? } ?>
    </ul>
  </div>
</div>
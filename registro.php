<? require_once 'clases/Pais.php';
require_once 'clases/Interes.php';
require_once 'clases/BD.php';
require_once 'funciones/sesion.php';
if(isset($_SESSION["usuario"])){
    header("Location: /");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Mapa Cafetero</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
        <link type="text/css" rel="stylesheet" href="css/estilo.css?ver=1.013"/>
        <link type="text/css" rel="stylesheet" href="css/registro.css?ver=1.004"/>
        <script type="text/javascript" src="js/registro.js?ver=1.08"></script>
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
        } 
        $oyente = null;
        if(isset($_SESSION["temp"])){
            $oyente = $_SESSION["temp"];
        } 
        $bd = new BD();
        ?>
        <h1>REGISTRO</h1>
        <form class="registro" method="post" action="funciones/controlador.php">
            <input type="hidden" name="accion" value="alta_usuario"/>
            <span>Cuenta de Twitter: </span> <input type="text" name="twitter" class="val" value="<?if(isset($oyente)){echo $oyente->getTwitter();}?>"/>
            <div class="clear"></div>
            <span>Pais donde resides: </span>
            <select name="pais" class="val">
                <option value="0">--Selecciona el país--</option>
                <? $arrPais = Pais::listar($bd);
                foreach ($arrPais as $pais) {
                    ?><option value="<?=$pais->getIdPais()?>" <?if(isset($oyente) && $oyente->getIdPais() == $pais->getIdPais()){echo "selected";}?>><?=$pais->getNombre()?></option><?
                } ?>
            </select>
            <div class="clear"></div>
            <hr/>
            <h2>La siguiente información es confidencial</h2>
            <span>Nombre: </span> <input type="text" name="nombre" class="val" value="<?if(isset($oyente)){echo $oyente->getNombre();}?>"/>
            <div class="clear"></div>
            <span>Apellido: </span> <input type="text" name="apellido" class="val" value="<?if(isset($oyente)){echo $oyente->getApellido();}?>"/>
            <div class="clear"></div>
            <span>Fecha de nacimiento: </span>
            <select name="anyo" class="val">
                <option value="0">--AÑO--</option>
                <? for($i = date(Y)-17; $i > date(Y)-100; $i--){ ?>
                <option value="<?=$i?>" <?if(isset($oyente) && date("Y", $oyente->getFechaNacimiento()) == $i){echo "selected";}?>><?=$i?></option>
                <? } ?>
            </select>/<select name="mes" class="val">
                <option value="0">--mes--</option>
                <? for($i = 1; $i < 13; $i++){
                    if($i < 10) {?>
                        <option value="0<?=$i?>" <?if(isset($oyente) && date("n", $oyente->getFechaNacimiento()) == $i){echo "selected";}?>><?=$i?></option>
                    <? }else{ ?>
                        <option value="<?=$i?>" <?if(isset($oyente) && date("n", $oyente->getFechaNacimiento()) == $i){echo "selected";}?>><?=$i?></option>
                    <? } ?>
                <? } ?>
            </select>/<select name="dia" class="val">
                <option value="0">--día--</option>
                <? for($i = 1; $i < 32; $i++){
                    if($i < 10) {?>
                        <option value="0<?=$i?>" <?if(isset($oyente) && date("j", $oyente->getFechaNacimiento()) == $i){echo "selected";}?>><?=$i?></option>
                    <? }else{ ?>
                        <option value="<?=$i?>" <?if(isset($oyente) && date("j", $oyente->getFechaNacimiento()) == $i){echo "selected";}?>><?=$i?></option>
                    <? } ?>
                <? } ?>
            </select>
            <div class="clear"></div>
            <span>Telefono (de estar en el extranjero, indica 00 y el código del pais): </span> <input type="text" name="telefono" class="val" value="<?if(isset($oyente)){echo $oyente->getTelefono();}?>"/>
            <div class="clear"></div>
            <hr/>
            <span>Intereses</span>
            <div class="intereses">
                <? $lista = Interes::listar($bd);
                foreach ($lista as $interes){ ?>
                <img src="images/<?=$interes->getFoto()?>" title="<?=$interes->getNombre()?>"/> <input type="checkbox" name="int_<?=$interes->getIdInteres()?>"/>
                <? } ?>
            </div>
            <h2>Información de registro</h2>
            <span>Email: </span> <input type="text" name="email" class="val" value="<?if(isset($oyente)){echo $oyente->getEmail();}?>"/>
            <div class="clear"></div>
            <span>Clave: </span> <input type="password" name="clave" class="val"/>
            <div class="clear"></div>
            <span>Repite la clave: </span> <input type="password" name="clave2" class="val"/>
            <div class="clear"></div>
            <span>Confirmo que he leido y acepto las <a href="#" onclick="ver_condiciones()">condiciones</a></span> <input type="checkbox" name="condiciones"/>
            <div class="clear"></div>
            <input type="submit" value="Registrar"/>
        </form>
        <div class="condiciones">
            <div class="caja">
                <h2>Aviso Legal y Política de Privacidad</h2>
                <p><b>Versión: Abril 2018</b></p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam velit enim, tincidunt at porttitor sed, tempus ac ipsum. Proin at viverra libero. Proin accumsan hendrerit est eget aliquet. Proin gravida risus a orci elementum, vel pulvinar quam pulvinar. Donec placerat vestibulum semper. Fusce vestibulum felis ac erat luctus blandit. Mauris a dolor nec orci tempus sollicitudin eget at turpis. Morbi ornare ultrices tincidunt. Nullam non velit ac massa fermentum placerat. Mauris hendrerit risus id eros sollicitudin ullamcorper.</p>
                <p>Morbi eget nunc a ipsum porttitor sodales. Vivamus eleifend bibendum quam. Quisque volutpat ultrices pharetra. Fusce velit erat, scelerisque vel egestas vel, interdum vitae mauris. Etiam consectetur in nulla id malesuada. Donec commodo elit libero, ut commodo lorem posuere vel. Nam ex lorem, vehicula a mi in, vulputate convallis sapien. Curabitur semper condimentum lacus non imperdiet. Maecenas vel posuere libero. Maecenas ut sem ut nisi tempus tempus sed pretium risus. Sed nec auctor dui, a varius est. Nunc vel massa magna. Duis volutpat suscipit magna, id fringilla leo rutrum et. In vel leo sollicitudin ex consequat ornare vel eu lectus.</p>
                <p>Nunc libero erat, consectetur sed aliquet sit amet, malesuada et nisi. Sed tellus neque, vestibulum volutpat dapibus at, maximus at sapien. Praesent sollicitudin neque non pulvinar tincidunt. Sed id dignissim augue, et congue sem. Pellentesque viverra diam a luctus tincidunt. Praesent ac urna et est suscipit placerat. Cras ac malesuada dolor. Curabitur ac tincidunt tortor. Quisque ut purus finibus, bibendum risus sit amet, vestibulum dui. Nullam in consectetur sapien, vel posuere massa. Morbi tempus vestibulum diam, eu mattis nisl. Cras venenatis posuere mauris, sit amet sollicitudin magna. Nulla aliquet odio vitae scelerisque aliquam. Nullam velit nisl, ultricies id varius vitae, porttitor sed quam.</p>
                <p>Suspendisse at mi posuere, luctus neque eu, porta sem. Vivamus tristique cursus purus vitae iaculis. Etiam convallis, neque posuere fringilla bibendum, ipsum lacus porttitor mauris, eget laoreet diam velit eget erat. Ut eleifend velit quis dapibus tristique. Cras condimentum lectus quis leo ullamcorper pharetra et eu elit. In vestibulum, arcu sed lacinia suscipit, lacus leo semper ligula, nec tempor sem nibh et enim. Quisque elementum ipsum nisi, sit amet facilisis ipsum sagittis nec. Duis consectetur purus quam, et facilisis velit gravida a. Vivamus vestibulum dapibus libero, ut rutrum magna. Praesent dapibus gravida diam a tempus.</p>
            </div>
        </div>
        <? $_SESSION["temp"] = null; ?>
    </body>
</html>

<? require_once 'clases/BD.php';
require_once 'funciones/sesion.php';

if(!isset($_SESSION["usuario"]) && !$_SESSION["usuario"]->getAdmin()){
    header("Location: /");
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Mapa Cafetero</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <script type="text/javascript" src="//scribblemaps.com/api/js/"></script>
        <script type="text/javascript" src="js/actualizador.js?ver=1.004"></script>
        <script type="text/javascript">
        var sm;

function agregarElemento(lat, lon, idOyente,twitter) {
    var coordenada = new scribblemaps.LatLng(lat, lon);
    sm.view.setCenter(coordenada);
    if(twitter.substring(0,1) == "@"){
        twitter = twitter.substring(1, twitter.length);
    }
    var marcador = sm.draw.marker(coordenada, '<a href="https://twitter.com/'+twitter+'" target="_blank">@'+twitter+'</a>', {'imgSrc': '//az766722.vo.msecnd.net/user/marker/2018/01/17/ZUxRP2.png'});
    marcador.setTitle(twitter);

    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'ajax/guardarCoordenadas.php?oyente='+idOyente+"&coordenada="+coordenada);
    xhr.onload = function() {
        if (xhr.status === 200) {
            if(xhr.responseText == "1");
            setTimeout(function(){
                sm.view.setZoom(2);
                var coordenada = new scribblemaps.LatLng(37.99616267972814, 1.0546875000000002);
                sm.view.setCenter(coordenada);
                var opciones = new scribblemaps.SaveOptions({
                    id: "ghQCV2cSHo",
                    password: 'cafemapa487693hu',
                    title: 'Mapa Cafetero',
                    descripcion: "Mapa de la resistencia cafetera",
                    listed: true,
                    secure: false,
                    projectId: null,
                    groupCode: null
                });
                sm.map.save(opciones, function(respuesta){
                },
                function(respuesta){
                    console.log(respuesta);
                });
            }, 1000);
        } else {
            console.log('Fallo en request. Estado: ' + xhr.status);
        }
    };
    xhr.send();
}

function modificarElemento() {
    sm = new ScribbleMap(document.getElementById('el_mapa'),
        {});
    sm.map.loadById("ghQCV2cSHo", function(){
        var listaMarcadores = sm.map.getOverlays();    
        for(var i in listaMarcadores){
            var marcador = listaMarcadores[i];
            marcador.remove();
        }

        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'ajax/getOyentes.php');
        xhr.onload = function() {
            if (xhr.status === 200) {
                var res = JSON.parse(xhr.responseText);
                for(var it in res){
                    var ele = res[it];
                    agregarElemento(ele.lat, ele.lon, ele.id_oyente, ele.twitter);
                }
            } else {
                console.log('Fallo en request. Estado: ' + xhr.status);
            }
        };
        xhr.send();
    });
}
        </script>
    </head>

    <body class="fondo" onload="modificarElemento();">
        <div id="el_mapa"></div>
    </body>
</html>

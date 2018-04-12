var sm;
var coder;
var muestraSugerencias;
var servicioAutocompletar = new google.maps.places.AutocompleteService();

var hacerClickMarca = function(){
    var ventana = document.getElementsByClassName('sm_infoWindow')[0];
    var ventanaInterior = ventana.getElementsByClassName('sm_infoWindow')[0];
    var cuenta = ventanaInterior.getElementsByClassName("enlaceTwitter")[0].innerHTML;
    var oyente = document.getElementsByName("id_oyente")[0].value;

    cuenta = cuenta.substring(1, cuenta.length);
    var img = document.createElement("img");
    var nombre = document.createElement("div");
    nombre.className = "nombre_twitter";
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'ajax/obtieneImagen.php?cuenta='+cuenta+"&oyente="+oyente);
    xhr.onload = function() {
        if (xhr.status === 200) {
            var respuesta = xhr.responseText.split("|");
            img.setAttribute("src", respuesta[0]);
            ventanaInterior.prepend(img);
            nombre.innerHTML = respuesta[1];
            ventanaInterior.prepend(nombre);
            if(respuesta[2] != "nn"){
                console.log(ventanaInterior.innerHTML = ventanaInterior.innerHTML + respuesta[2]);
                ventanaInterior.className += " ampliado";
                ventana.className += " ampliado";
            }
        } else {
            console.log('Fallo en request. Estado: ' + xhr.status);
        }
    };
    xhr.send();
};

window.onload = function () {
    sm = new ScribbleMap(document.getElementById('el_mapa'),
        {});
    sm.map.loadById("ghQCV2cSHo", function(){
        sm.ui.showCrosshairs();
        sm.ui.addListener('infowindow_open', hacerClickMarca);
    });
    coder = new google.maps.Geocoder();
    servicioAutocompletar  = new google.maps.places.AutocompleteService();

    muestraSugerencias = function(predictions, status){
        if(status != google.maps.places.PlacesServiceStatus.OK) {
            return;
        }

        for(var iter in predictions){
            var prediction = predictions[iter];
            var opcion = document.createElement('div');
            opcion.setAttribute("class", "opcion");
            opcion.innerHTML = prediction.description;
            opcion.setAttribute("onclick", "elegirOpcion(this)");
            document.getElementById("sugerencias").appendChild(opcion);
        }
    };
};

function buscarPunto(){
    var direccion = document.getElementById('buscador').value;
    coder.geocode({'address':direccion}, function(respuesta, estado){
        var peque = true;
        if(estado == 'OK'){
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'ajax/esPais.php?pais='+direccion);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    if(xhr.responseText == 1){
                        peque = false;
                    }
                    var coordenada = new scribblemaps.LatLng(respuesta[0].geometry.location.lat(), respuesta[0].geometry.location.lng());
                    sm.view.setCenter(coordenada);
                    if(peque){
                        sm.view.setZoom(18);
                    }else{
                        sm.view.setZoom(8);
                    }
                    sm.ui.showCrosshairs();
                } else {
                    console.log('Fallo en request. Estado: ' + xhr.status);
                }
            };
            xhr.send();
        }
    });
}

function verPosicion(){
    var coord = sm.view.getCenter();
    console.log(coord.lat+" - "+coord.lng);
}

function buscarSugerencias(){
    document.getElementById("sugerencias").innerHTML = "";
    servicioAutocompletar.getQueryPredictions({input: document.getElementById('buscador').value}, muestraSugerencias);
}

function elegirOpcion(ele){
    var texto = ele.innerHTML;
    document.getElementById('buscador').value = texto;
    document.getElementById("sugerencias").innerHTML = "";
    buscarPunto();
}
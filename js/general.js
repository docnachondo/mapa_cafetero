function quitarCarga(){
    $('body > div.centro').animate(
    {'opacity':0},
        500,
        function(){
            $('body > div.centro').remove();
        }
    );

    $('body > div.fondo').animate(
        {'opacity':0},
        500,
        function(){
            $('body > div.fondo').remove();
        }
    );
}

function trim(myString) {
    return myString.replace(/^\s+/g, '').replace(/\s+$/g, '')
}

function mostrar(elemento) {
    $(elemento).show(500);
}

function cerrar(elemento) {
    $(elemento).hide(500);
}

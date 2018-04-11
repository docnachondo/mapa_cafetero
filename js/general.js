function trim(myString) {
    return myString.replace(/^\s+/g, '').replace(/\s+$/g, '');
}

function mostrar(elemento) {
    $(elemento).show(500);
}

function cerrar(elemento) {
    $(elemento).hide(500);
}
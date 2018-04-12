$(document).ready(function(){
    $('input.val').keyup(function(){
        validar(this);
    });
    
    $('.val').blur(function(){
        validar(this);
    });
	
    $('form').submit(function(){
        var res = true;
        $(this).find('.val').each(function(num, ele){
            if(!validar($(ele))){
                res = false;
            }
        });
        return res;
    });
});

function validar(obj){
    console.log("Valido campo: "+$(obj).attr('name'));
    if(checkCampo(obj)){
        console.log("OK");
        $(obj).addClass("valido");
        $(obj).removeClass("invalido");
        return true;
    }else{
        console.log("NO OK");
        $(obj).removeClass("valido");
        $(obj).addClass("invalido");
        return false;
    }
}

function checkCampo(obj){
    switch($(obj).attr('name')){
        case "dia":
            if($(obj).val() === "0"){
                return false;
            }else{
                return validaFecha();
            }
            break;

        case "anyo":
        case "mes":
        case "pais":
            if($(obj).val() === "0"){
                return false;
            }else{
                return true;
            }
            break;
        
        case "telefono":
            if($(obj).val().length === 0 || $(obj).val().length > 7){
                return true;
            }else{
                return false;
            }
            break;

        case "nombre":
        case "twitter":
        case "apellido":
            if($(obj).val().length > 2){
                return true;
            }else{
                return false;
            }
            break;

        case "twitter":
        case "clave":
            if($(obj).val().length > 5){
                return true;
            }else{
                return false;
            }
            break;

        case "clave2":
            if($(obj).val().length > 5){
                return validaClave();
            }else{
                return false;
            }
            break;

        case "email":
            if (!trim($(obj).val()).match("^[_a-z0-9-]+(\\.[_a-z0-9-]+)*@[a-z0-9-]+(\\.[a-z0-9-]+)*(\\.[a-z]{2,3})$")) {
                return false;
            } else {
                return true;
            }
            break;

        case "condiciones":
            if($("input[name=condiciones]:checked").length > 0){
                return true;
            }else{
                return false;
            }
            break;
    }
    if($(obj).attr('type') === "submit"){
        return true;
    }
}

function validaFecha(){
    var mes = parseInt($('input[name=mes]').val());
    var dia = parseInt($('input[name=dia]').val());

    switch(mes){
        case 4:
        case 6:
        case 9:
        case 11:
            if(dia > 30){
                return false;
            }else{
                return true;
            }
            break;
            
        case 2:
            if(parseInt($('input[name=anyo]').val())%4 === 0){
                if(dia > 29){
                    return false;
                }else{
                    return true;
                }
            }else{
                if(dia > 28){
                    return false;
                }else{
                    return true;
                }
            }
            break;
            
        default:
            return true;
            break;
    }
}

function validaClave(){
    if($('input[name=clave]').val() == $('input[name=clave2]').val()){
        return true;
    }else{
        return false;
    }
}
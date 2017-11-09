<?php

function dameDato($dato){
    echo '<pre>';
    print_r($dato);
    echo '</pre>';
    die();
}
/**
 * Esta función genera un bloque de alerta para mostrar cuando se producen errores de validación.
 *
 * @param $errors       Array con la información de los errores
 * @param $field        String con el nombre del campo a evaluar
 * @return null|string  Código HTML del error
 */
function generarAlert($errors, $field){
    // Si hay errores en ese campo:
    if( isset($errors[$field]) ){
        // Se crea un string con la lista de errores
        $errorList = '';
        foreach ($errors[$field] as $error) {
            $errorList .= "{$error}<br>";
        }

        // Y se inserta dicha lista en un bloque alert (ver documentación bootstrap 3.3.7)
        $alert = <<<ALERT
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>{$errorList}</strong>
                </div>
ALERT;
    }else{
        $alert = null;
    }

    return $alert;
}
?>
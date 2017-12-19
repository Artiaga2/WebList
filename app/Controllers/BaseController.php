<?php
namespace App\Controllers;

class BaseController {

    public $templateEngine;

    public function __construct()
    {
        $loader = new \Twig_Loader_Filesystem('../views');
        $this->templateEngine = new \Twig_Environment($loader, [
            'debug' => true,
            'cache' => false
        ]);

        $this->templateEngine->addGlobal('session', $_SESSION);

        $this->templateEngine->addFilter(new \Twig_SimpleFilter('url', function($path){
            return BASE_URL . $path;
        }));

        $this->templateEngine->addFunction(new \Twig_Function('generateAlert', function ($errors, $field){
            if( isset($errors[$field]) ){
                $errorList = '';
                foreach ($errors[$field] as $error) {
                    $errorList .= "{$error}<br>";
                }

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
        }, ['is_safe' => ['html']]));
    }


//        $this->templateEngine->addFunction(new \Twig_Function('generateSelect', function($listaValores, $seleccionados, $name, $multiple = false){
//            $salida = '<select class="form-control" name="'.$name.($multiple?"[]":""). '"' . ($multiple?"multiple":"") .'>';
//
//            if( !is_array($seleccionados) ){
//                $seleccionados = explode(", ", $seleccionados);
//            }
//
//            foreach ($listaValores as $valor){
//                $selected = "";
//                if( in_array($valor, $seleccionados) ) $selected = " selected";
//                $salida .= "<option value=\"{$valor}\"{$selected}>{$valor}</option>";
//            }
//
//            $salida .= '</select>';
//
//            return $salida;
//        }, ['is_safe' => ['html']]));
//    }

    public function render($fileName, $data){
        return $this->templateEngine->render($fileName,$data);
    }
}
<?php

require_once('form.php');
require_once('usuario.php');
#require_once('entrenador.php');

class formularioCliente extends Form{

    public function  __construct($formId, $opciones = array() ){
        parent::__construct($formId, $opciones);
    }


    /**
     * Genera el HTML necesario para presentar los campos del formulario.
     *
     * @param string[] $datosIniciales Datos iniciales para los campos del formulario (normalmente <code>$_POST</code>).
     * 
     * @return string HTML asociado a los campos del formulario.
     */
    protected function generaCamposFormulario($datosIniciales){

        $html = '<div class="formulario">';

        $html .= '<div class="grupo-control">';
        $html .= '<label>id de la Pulsera:</label> <input class="control" type="text" name="id" />';
        $html .= '</div>';

        $html .= '<div class="grupo-control"><button type="submit" name="registro">Editar</button></div>';


        $html .= '</div>';
        return $html;
    }

    protected function procesaFormulario($datos){

        $erroresFormulario = array();

        

        $id = isset($_POST['id']) ? $_POST['id'] : null;
        if (empty($id) || mb_strlen($id)<10){
            $erroresFormulario[]= "El id introducido para la pulsera no es valido.";
        }

        $usuario= Usuario::buscaUsuario($id);
        if(!$usuario){
            $erroresFormulario[]="El usuario introducido no existe.";
        }

        if($usuario->entrenador()){
            $erroresFormulario[]="El usuario ya tiene un entrenador.";
        }
        
        if (count($erroresFormulario) === 0) {
            $usuario->setEntrenador($_SESSION['nombre']);
            #return "perfil.php";
            
        }

        return $erroresFormulario;

    }

}
?>

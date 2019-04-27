<?php

require_once('form.php');
require_once('entrenador.php');


class formularioLoginE extends Form{

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
        $html ='<div class="formulario">';
        $html .= '<div class="grupo-control">';                            
        $html .= '<label>Nombre:</label> <input type="text" name="id" />';
        $html .= '</div>';
        $html .= '<div class="grupo-control">';
        $html .= '<label>Password:</label> <input type="password" name="password" />';
        $html .= '</div>';
        $html .= '<div class="grupo-control"><button type="submit" name="login">Entrar</button></div>';
        $html .= '<div class="grupo-control">';
        $html .= '<a href="entrenadorRegistro.php" class="entrenador">No estoy dado de alta.</a>';
        $html .= '</div>';
        $html .='</div>';


        return $html;
    }

    protected function procesaFormulario($datos){

        $erroresFormulario = array();

        $id = isset($datos['id']) ? $datos['id'] : null;

        if ( empty($id) ) {
            $erroresFormulario[] = "El nombre de usuario no puede estar vacío";
        }

        $password = isset($datos['password']) ? $datos['password'] : null;
        if ( empty($password) ) {
            $erroresFormulario[] = "El password no puede estar vacío.";
        }

        if (count($erroresFormulario) === 0) {
            //$app esta incluido en config.php


            $entrenador = Entrenador::searchTrainer($id);

			
            if (!$entrenador) {
                $erroresFormulario[] = "El usuario o el password no coinciden";
            }
            else{
                
                
                if ($entrenador->compruebaPassword($password)) {
                    $_SESSION['login'] = true;
                    $_SESSION['esEntrenador'] = true;
                    $_SESSION['nombre']=$entrenador->nombre();
                    return "perfil.php";
                }
                else {
                    $erroresFormulario[] = "El usuario o el password no coinciden";
                }

                
            }
        }

        return $erroresFormulario;
    }

}
?>
<?php

require_once('form.php');
require_once('usuario.php');


class formularioLogin extends Form{

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

        $html = '<div class="grupo-control">';                            
        $html .= '<label>Id Pulsera:</label> <input type="text" name="idPulsera" />';
        $html .= '</div>';
        $html .= '<div class="grupo-control">';
        $html .= '<label>Password:</label> <input type="password" name="password" />';
        $html .= '</div>';
        $html .= '<div class="grupo-control"><button type="submit" name="login">Entrar</button></div>';

        return $html;
    }

    protected function procesaFormulario($datos){

        $erroresFormulario = array();

        $idPulsera = isset($datos['idPulsera']) ? $datos['idPulsera'] : null;

        if ( empty($idPulsera) ) {
            $erroresFormulario[] = "El nombre de usuario no puede estar vacío";
        }

        $password = isset($datos['password']) ? $datos['password'] : null;
        if ( empty($password) ) {
            $erroresFormulario[] = "El password no puede estar vacío.";
        }

        if (count($erroresFormulario) === 0) {
            //$app esta incluido en config.php


            $usuario = Usuario::buscaUsuario($idPulsera);
			
            if (!$usuario) {
                $erroresFormulario[] = "El usuario o el password no coinciden";
            }
            else{
                
                
                if ($usuario->compruebaPassword($password)) {
                    $_SESSION['login'] = true;
                    $_SESSION['idPulsera'] = $idPulsera;
                    $_SESSION['nombre'] = $usuario->nombre();
                    $_SESSION['esAdmin'] = strcmp($fila['rol'], 'admin') == 0 ? true : false;
                    //header('Location: index.php');
                    return "index.php";
                } else {
                    $erroresFormulario[] = "El usuario o el password no coinciden";
                }
            }
        }

        return $erroresFormulario;
    }

}
?>
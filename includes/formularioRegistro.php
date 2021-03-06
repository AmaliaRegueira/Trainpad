<?php

require_once('form.php');
require_once('usuario.php');

class formularioRegistro extends Form{

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
        
        $html .= '<label>Nombre de usuario:</label><input class="control" type="text" name="username" />';
        $html .= '</div>';

        $html .= '<div class="grupo-control">';
        $html .= '<label>Correo electrónico:</label><input class="control" type="text" name="email" />';
        $html .= '</div>';

        $html .= '<div class="grupo-control">';
        $html .= '<label>idPulsera:</label><input class="control" type="text" name="idPulsera">';
        $html .= '</div>';

        $html .= '<div class="grupo-control">';
        $html .= '<label>Password:</label><input class="control" type="password" name="password" />';
        $html .= '</div>';

        $html .= '<div class="grupo-control">';
        $html .= '<label>Repita Password:</label><input class="control" type="password" name="password2" />';
        $html .= '</tr>';
        $html .= '</div>';

        $html .= '<div class="grupo-control"';
        $html .= '<label>Edad:</lable><input class="control" type="text" name="edad">';
        $html .= '</div>';


        $html .= '<div class="checkbox">';
        $html .= '<div>';
        $html .= '<input class="control" type="checkbox" name="accept"/><label>   Acepto los términos y condiciones.</label>';
        $html .= '</div>';

        $html .= '<div>';
        $html .= '<input class="control" type="checkbox" name="robot"/><label for="robot">   No soy un robot.</label>';
        $html .= '</div>';
        $html .= '</div>';

        $html .= '<div class="boton"><button type="submit" name="registro">Registrar</button></div>';

        return $html;
    }

    protected function procesaFormulario($datos){

        $erroresFormulario = array();

        $username = isset($_POST['username']) ? $_POST['username'] : null;
        if ( empty($username) || mb_strlen($username) < 5 ) {
            $erroresFormulario[] = "El nombre de usuario tiene que tener una longitud de al menos 5 caracteres.";
        }
        
        $email = isset($_POST['email']) ? $_POST['email'] : null;
        if ( empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) ) {
            $erroresFormulario[] = "El email no tiene un formato valido.";
        }

        $idPulsera = isset($_POST['idPulsera']) ? $_POST['idPulsera'] : null;
        if (empty($idPulsera) || mb_strlen($idPulsera)<10){
            $erroresFormulario[]= "El id introducido para la pulsera no es valido";
        }
        
        $password = isset($_POST['password']) ? $_POST['password'] : null;
        if ( empty($password) || mb_strlen($password) < 5 ) {
            $erroresFormulario[] = "La password tiene que tener una longitud de al menos 5 caracteres.";
        }
        $password2 = isset($_POST['password2']) ? $_POST['password2'] : null;
        if ( empty($password2) || strcmp($password, $password2) !== 0 ) {
            $erroresFormulario[] = "Las passwords deben coincidir.";
        }

        $edad = isset($_POST['edad']) ? $_POST['edad'] : null;
        if (empty($edad) || $edad<18){
            $erroresFormulario[]= "La edad no es valida, debe tener al menos 18 años.";
        }


        $accept = isset($_POST['accept']) ? $_POST['accept'] : null; 
        if (empty($accept) || !$accept){
            $erroresFormulario[] = "Debes acceptar los términos y condiciones.";
        }

        $robot = isset($_POST['robot']) ? $_POST['robot'] : null;
        if (empty($robot) || !$robot){
            $erroresFormulario[] = "Debes confirmar que no eres un robot.";
        }
        
        if (count($erroresFormulario) === 0) {
            $usuario = Usuario::crea($idPulsera, $username, $email, $password, $edad, 'deportista');
            
            if (! $usuario ) {
                $erroresFormulario[] = "El usuario ya existe";
            } else {
                $_SESSION['login'] = true;
                $_SESSION['id'] = $idPulsera;
                $_SESSION['nombre']= $username;
                $_SESSION['isAdmin'] =($usuario->rol()==='admin')? true : false;
                //header('Location: index.php');

                /*Crea la carpeta correspondiente al usuario en /mysql/img/ (relacionado con
                el procesamiento del formularioSubirMeme)*/

                
                


                return "perfil.php";
            }
        }

        return $erroresFormulario;

    }

}
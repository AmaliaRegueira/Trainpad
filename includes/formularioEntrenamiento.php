<?php

require_once('form.php');
require_once('usuario.php');


class formularioEntrenamiento extends Form{

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
        $html ='<div id="formEntrenamiento">';
        $html .= '<div class="grupo-control">';
        
        $html .= '<label>Fecha:</label><input class="control" type="date" name="fecha" />';
        $html .= '</div>';

        $html .= '<div class="grupo-control">';
        $html .= '<label>Hora inicio:</label><input class="control" type="time" name="inicio" />';
        $html .= '</div>';

        $html .= '<div class="grupo-control">';
        $html .= '<label>Actividad:</label><input class="control" type="text" name="actividad">';
        $html .= '</div>';

        $html .= '<div class="grupo-control">';
        $html .= '<label>Hora fin:</label><input class="control" type="time" name="fin" />';
        $html .= '</div>';

        $html .= '<div class="boton"><button type="submit" name="registro">Registrar</button></div>';
        $html .= '</div>';

        return $html;
    }

    protected function procesaFormulario($datos){

        $erroresFormulario = array();

        $fecha = isset($datos['fecha']) ? $datos['fecha'] : null;

        if ( empty($fecha) ) {
            $erroresFormulario[] = "La fecha no puede estar vacío";
        }

        $inicio = isset($datos['inicio']) ? $datos['inicio'] : null;
        if ( empty($inicio) ) {
            $erroresFormulario[] = "La hora de inicio no puede estar vacío.";
        }

        $actividad = isset($datos['actividad']) ? $datos['actividad'] : null;
        if(empty($actividad)){
            $erroresFromulario[] = "La actividad no puede ser vacío.";
        }

        $fin = isset($datos['fin']) ? $datos['fin'] : null;
        if(empty($actividad)){
            $erroresFormulario[] = "La hora de fin no puede ser vacío";
        }

        if (count($erroresFormulario) === 0) {
            //$app esta incluido en config.php

            $id=$_SESSION['idPulsera'];
                
            $sesion=Sesion::crea($fecha, $inicio, $actividad, $fin, $id);
                
            return "index.php";
                
            
        }

        return $erroresFormulario;
    }

}
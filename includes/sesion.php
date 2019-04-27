<?php

require_once('aplicacion.php');

class Sesion {

    private $fecha;
    private $inicio;
    private $actividad;
    private $fin;
    private $idPulsera;


    private function __construct($fecha, $inicio, $actividad, $fin){
        /*$this->idPulsera= $idPulsera;*/
        $this->fecha= $fecha;
        $this->inicio = $inicio;
        $this->actividad = $actividad;
        $this->fin = $fin;
    }

    public function inicio(){ 
        return $this->inicio; 
    }

    public function fecha(){
        return $this->fecha;
    }
    public function fin(){
        return $this->fin;
    }

    public function actividad(){ 
        return $this->actividad;
     }

    /* Devuelve un objeto Usuario con la información del usuario $nombre,
     o false si no lo encuentra*/
    public static function buscaSesion($sesion){
        $app = Aplicacion::getInstance();
        $conn = $app->conexionBD();

        $query = sprintf("SELECT * FROM sesión U WHERE U.idPulsera = '%s' AND U.Fecha='%s' AND U.HoraIni='%s' AND U.HoraFin= '%s'"
            ,$conn->real_escape_string($sesion->idPulsera)
            ,$conn->real_escape_string($sesion->fecha)
            ,$conn->real_escape_string($sesion->inicio)
            ,$conn->real_escape_string($sesion->fin));
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            if ( $rs->num_rows == 1) {
                $fila = $rs->fetch_assoc();
                $user = new Sesion($fila['Fecha'], $fila['HoraIni'], $fila['Deporte'], $fila['HoraFin']);
                $user->idPulsera = $fila['idPulsera'];
                $result = $user;
            }
            $rs->free();
        } else {
            echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $result;
    }


    private static function interfiereSesion($sesion){
        $app = Aplicacion::getInstance();
        $conn = $app->conexionBD();
        var_dump($sesion);
        $query = sprintf("SELECT * FROM sesión U WHERE U.idPulsera='%s' AND U.Fecha='%s' AND ('%s' BETWEEN U.HoraIni AND U.HoraFin OR '%s' BETWEEN U.HoraIni AND U.HoraFin)"
        , $conn->real_escape_string($sesion->idPulsera)
        , $conn->real_escape_string($sesion->fecha())
        , $conn->real_escape_string($sesion->inicio())
        , $conn->real_escape_string($sesion->fin()));

        $rs = $conn->query($query);
        
        if($rs){
            if ($rs->num_rows>0){
                return true;

            }
            else{var_dump("no entro en el if");}
            
        }
        else{
            echo "Error al consultar en la BD: (". $conn->errno .")". utf8_encode($conn->error);
            exit;
        }
        return false;
    }
    
    /* Crea un nuevo usuario con los datos introducidPulseraos por parámetro. */
    public static function crea($fecha, $inicio, $actividad, $fin,$id){
        $sesion = new Sesion($fecha, $inicio, $actividad, $fin);

        $sesion->idPulsera=$id;

        $existe = self::buscaSesion($sesion);
        
        $interfiere = self::interfiereSesion($sesion);
        if ($existe || $interfiere) {
            var_dump("hola");
            return false;

        }
        
         $ret =self::insert($sesion);
         return $ret;
    }
    
    private static function insert($sesion){
        $app = Aplicacion::getInstance();
        $conn = $app->conexionBD();
        $query=sprintf("INSERT INTO sesión(Fecha, HoraIni, HoraFin, Deporte, idPulsera) VALUES('%s', '%s', '%s', '%s', '%s')"
            , $conn->real_escape_string($sesion->fecha)
            , $conn->real_escape_string($sesion->inicio)
            , $conn->real_escape_string($sesion->fin)
            , $conn->real_escape_string($sesion->actividad)
            , $conn->real_escape_string($sesion->idPulsera));

        if ( !$conn->query($query) ){
            echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        
        return $sesion;
    }
    
    private static function actualiza($usuario){
        $app = Aplicacion::getInstance();
        $conn = $app->conexionBD();
        $query=sprintf("UPDATE sesion U SET Fecha = '%s', HoraIni='%s', HoraFin='%s', Actividad='%s' WHERE U.idPulsera=%i"
            , $conn->real_escape_string($usuario->nombre)
            , $conn->real_escape_string($usuario->email)
            , $conn->real_escape_string($usuario->password)
            , $conn->real_escape_string($usuario->edad)
            , $usuario->idPulsera);
        if ( $conn->query($query) ) {
            if ( $conn->affected_rows != 1) {
                echo "No se ha podido actualizar el usuario: " . $usuario->idPulsera;
                exit();
            }
        } else {
            echo "Error al actualizar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        
        return $usuario;
    }
    
}

<?php

require_once('aplicacion.php');

class Usuario {

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

    public function idPulsera(){ 
        return $this->idPulsera; 
    }

    public function nombre(){
        return $this->nombre;
    }
    public function edad(){
        return $this->edad;
    }

    public function rol(){ 
        return $this->rol;
     }

    

    public function cambiaPassword($nuevoPassword){
        $this->password = self::hashPassword($nuevoPassword);
    }


    /* Devuelve un objeto Usuario con la información del usuario $nombre,
     o false si no lo encuentra*/
    public static function buscaUsuario($idPulsera){
        $app = Aplicacion::getInstance();
        $conn = $app->conexionBD();

        $query = sprintf("SELECT * FROM deportista U WHERE U.idPulsera = '%s'", $conn->real_escape_string($idPulsera));
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            if ( $rs->num_rows == 1) {
                $fila = $rs->fetch_assoc();
                $user = new Usuario($fila['Nombre'], $fila['Email'], $fila['Password'], $fila['Edad'], $fila['Rol']);
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

    /*Comprueba si la contraseña introducidPulseraa coincidPulserae con la del Usuario.*/
    public function compruebaPassword($password){
        return password_verify($password, $this->password);
    }

    /* Devuelve un objeto Usuario si el usuario existe y coincidPulserae su contraseña. En caso contrario,
     devuelve false.*/
    public static function login($idPulsera, $password){
        $user = self::buscaUsuario($idPulsera);
        if ($user && $user->compruebaPassword($password)) {
            return $user;
        }
        return false;
    }
    
    /* Crea un nuevo usuario con los datos introducidPulseraos por parámetro. */
    public static function crea($fecha, $inicio, $actividad, $fin,$usuario){
        $sesion = self::buscaSesion($fecha, $inicio, $fin);
        if ($sesion) {
            return false;
        }
        $sesino = self::interfiereFuncion($fecha, $inicio, $fin);
        if($sesion){
            return false;
        }
        $sesion = new Sesion($fecha, $inicio, $actividad, $fin);
        $sesion->idPulsera = $idPulsera;
        return self::inserta($user);
    }
    
    private static function inserta($usuario){
        $app = Aplicacion::getInstance();
        $conn = $app->conexionBD();
        $query=sprintf("INSERT INTO deportista(idPulsera, Nombre, Email, Password, Edad, Rol) VALUES('%s', '%s', '%s', '%s', '%s', '%s')"
            , $conn->real_escape_string($usuario->idPulsera)
            , $conn->real_escape_string($usuario->nombre)
            , $conn->real_escape_string($usuario->email)
            , $conn->real_escape_string($usuario->password)
            , $conn->real_escape_string($usuario->edad)
            , $conn->real_escape_string($usuario->rol));

        if ( $conn->query($query) ){
            $usuario->idPulsera = $conn->insert_idPulsera;
        } else {
            echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $usuario;
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

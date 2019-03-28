<?php

require_once('aplicacion.php');

class Usuario {

    private $idPulseraPulsera;
    private $nombre;
    private $email;
    private $password;
    private $edad;
    private $rol;


    private function __construct($idPulsera, $nombre, $email, $password, $edad, $rol){
        $this->idPulsera= $idPulsera;
        $this->nombre= $nombre;
        $this->email = $email;
        $this->password = $password;
        $this->rol = $rol;
    }

    public function idPulsera(){ 
        return $this->idPulsera; 
    }

    public function nombre(){
        return $this->nombre;
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
                $user = new Usuario($idPulsera, $fila['Nombre'], $fila['Email'], $fila['Password'], $fila['Edad'], $fila['Rol']);
                //$user->idPulsera = $fila['idPulsera'];
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
    public static function crea($idPulsera, $nombre, $email, $password,$edad, $rol){
        $user = self::buscaUsuario($idPulsera);
        if ($user) {
            return false;
        }
        $user = new Usuario(null, $nombre, $email, password_hash($password, PASSWORD_DEFAULT),$edad, $rol);
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
        $query=sprintf("UPDATE deportista U SET Nombre = '%s', Email='%s', Password='%s', Edad='%s' Rol='%s' WHERE U.idPulsera=%i"
            , $conn->real_escape_string($usuario->nombre)
            , $conn->real_escape_string($usuario->email)
            , $conn->real_escape_string($usuario->password)
            , $conn->real_escape_string($usuario->edad)
            , $conn->real_escape_string($usuario->rol)
            , $usuario->idPulsera);
        if ( $conn->query($query) ) {
            if ( $conn->affected_rows != 1) {
                echo "No se ha podidPulserao actualizar el usuario: " . $usuario->idPulsera;
                exit();
            }
        } else {
            echo "Error al actualizar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        
        return $usuario;
    }

/*    public static function memes($nombre){
        $usuario = self::buscaUsuario($nombre);
        $app = Aplicacion::getInstance();
        $conn = $app->conexionBD();
        $query=sprintf("SELECT link_img FROM memes WHERE idPulsera_autor= '%s'", $conn->real_escape_string($usuario->idPulsera()));
        $rs = $conn->query($query);
        $rt=false;
      
        if ($rs){
            if($rs->num_rows>0){
                
                $rt=array();
                $i = 0;
                while($row = mysqli_fetch_assoc($rs)){
                    $rt[$i] = $row['link_img'];
                    $i = $i + 1;
                }
            }
            $rs->free();
        }
        else{
            echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit(); 
        }
        return $rt;
    }*/
    
}

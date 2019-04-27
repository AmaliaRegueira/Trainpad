<?php

require_once('aplicacion.php');

class Usuario {

    private $idPulsera;
    private $nombre;
    private $email;
    private $password;
    private $edad;
    private $rol;
    private $entrenador;


    private function __construct($nombre, $email, $password, $edad, $rol, $entrenador){
        /*$this->idPulsera= $idPulsera;*/
        $this->nombre= $nombre;
        $this->email = $email;
        $this->password = $password;
        $this->edad = $edad;
        $this->rol = $rol;
        $this->entrenador =$entrenador;
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

    public function email(){
        return $this->email;
    }

    public function rol(){ 
        return $this->rol;
    }

    public function entrenador(){
        return $this->entrenador;
    }

    public function setUserName($name){
        $this->nombre = $name;
    }

    public function setEntrenador($nombre){
        $this->entrenador= $nombre;
        self::update($this);

    }

    public function cambiaPassword($nuevoPassword){
        $this->password = password_hash($nuevoPassword, PASSWORD_DEFAULT);
    }


    /* Devuelve un objeto Usuario con la información del usuario $nombre,
     o false si no lo encuentra*/
    public static function buscaUsuario($idPulsera){
        $app = Aplicacion::getInstance();
        $conn = $app->conexionBD();

        $query = sprintf("SELECT * FROM deportistas U WHERE U.idPulsera = '%s'", $conn->real_escape_string($idPulsera));
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            if ( $rs->num_rows == 1) {
                $fila = $rs->fetch_assoc();
                $user = new Usuario($fila['Nombre'], $fila['Email'], $fila['Password'], $fila['Edad'], $fila['Rol'], $fila['Entrenador']);
                
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
    public static function crea($idPulsera, $nombre, $email, $password,$edad, $rol){
        $user = self::buscaUsuario($idPulsera);
        if ($user) {
            return false;
        }
        $user = new Usuario($nombre, $email, password_hash($password, PASSWORD_DEFAULT),$edad, $rol, NULL);
        $user->idPulsera = $idPulsera;
        return self::inserta($user);
    }
    
    private static function inserta($usuario){
        $app = Aplicacion::getInstance();
        $conn = $app->conexionBD();
        $query=sprintf("INSERT INTO deportistas(idPulsera, Nombre, Email, Password, Edad, Rol) VALUES('%s', '%s', '%s', '%s', '%s', '%s')"
            , $conn->real_escape_string($usuario->idPulsera)
            , $conn->real_escape_string($usuario->nombre)
            , $conn->real_escape_string($usuario->email)
            , $conn->real_escape_string($usuario->password)
            , $conn->real_escape_string($usuario->edad)
            , $conn->real_escape_string($usuario->rol));

        if ( !$conn->query($query) ){
            echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $usuario;
    }
    
    public static function update($usuario, $nombre= NULL){
        $app = Aplicacion::getInstance();
        $conn = $app->conexionBD();
        var_dump($usuario);
        $query=sprintf("UPDATE deportistas U SET Nombre = '%s', Email='%s', Password='%s', Edad='%s', Rol='%s', Entrenador='%s' WHERE U.idPulsera='%s'"
            , $conn->real_escape_string($usuario->nombre)
            , $conn->real_escape_string($usuario->email)
            , $conn->real_escape_string($usuario->password)
            , $conn->real_escape_string($usuario->edad)
            , $conn->real_escape_string($usuario->rol)
            , $conn->real_escape_string($usuario->entrenador)
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

    public static function sesiones($idPulsera){
        $usuario = self::buscaUsuario($idPulsera);

        $app = Aplicacion::getInstance();
        $conn= $app->conexionBD();
        $query = sprintf("SELECT * FROM sesión WHERE idPulsera='%s' ORDER BY Fecha DESC ", $conn->real_escape_string($usuario->idPulsera()));
        $rs = $conn->query($query);
        $rt=false;

        if ($rs){
            if($rs->num_rows>0){
                $row = mysqli_fetch_assoc($rs);
                $rt = "<div class= 'grupo'>";
                $rt .= "<h3>Fecha: </h3><label>".$row['Fecha']."</label><h3> Inicio: </h3><label>".$row['HoraIni']."-".$row['HoraFin']."</label><h3> Actividad: </h3><label>".$row['Deporte']."</label>";
                $rt .= "</div>";
                
                while($row = mysqli_fetch_assoc($rs)){
                    $rt .= "<div class= 'grupo'>";
                    $rt .= "<h3>Fecha: </h3><label>".$row['Fecha']."</label><h3> Inicio: </h3><label>".$row['HoraIni']."-".$row['HoraFin']."</label><h3> Actividad: </h3><label>".$row['Deporte']."</label>";
                    $rt .= "</div>";
                }
            }
            $rs->free();
        }
        else{
            echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit(); 
        }
        return $rt;

    }

    
}

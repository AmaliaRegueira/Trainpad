<?php

require_once('aplicacion.php');

class Entrenador {

    private $id;
    private $nombre;
    private $email;
    private $password;
    private $rol;


    private function __construct($nombre, $email, $password, $rol){
        /*$this->idPulsera= $idPulsera;*/
        $this->nombre= $nombre;
        $this->email = $email;
        $this->password = $password;
        $this->rol = $rol;
    }

    public function id(){ 
        return $this->id; 
    }

    public function nombre(){
        return $this->nombre;
    }

    public function email(){
        return $this->email;
    }

    public function rol(){ 
        return $this->rol;
     }

    

    public function changePassword($nuevoPassword){
        $this->password = self::hashPassword($nuevoPassword);
    }


    /* Devuelve un objeto Usuario con la información del usuario $nombre,
     o false si no lo encuentra*/
    public static function searchTrainer($nombre){
        $app = Aplicacion::getInstance();
        $conn = $app->conexionBD();

        $query = sprintf("SELECT * FROM entrenador U WHERE U.Nombre = '%s'", $conn->real_escape_string($nombre));
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            if ( $rs->num_rows == 1) {
                $fila = $rs->fetch_assoc();
                $user = new Usuario($fila['Nombre'], $fila['Email'], $fila['Password'], $fila['Rol']);
                $user->id = $fila['id'];
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
        $user = self::searchTrainer($idPulsera);
        if ($user && $user->compruebaPassword($password)) {
            return $user;
        }
        return false;
    }
    
    /* Crea un nuevo usuario con los datos introducidPulseraos por parámetro. */
    public static function crea($nombre, $email, $password, $rol){
        $user = self::searchTrainer($nombre);
        if ($user) {
            return false;
        }
        $user = new Entrenador($nombre, $email, password_hash($password, PASSWORD_DEFAULT), $rol);
        
        return self::insert($user);
    }
    
    private static function insert($usuario){
        $app = Aplicacion::getInstance();
        $conn = $app->conexionBD();
        var_dump($usuario->rol);
        $query=sprintf("INSERT INTO entrenador(Nombre, Email, Password, Rol) VALUES('%s', '%s', '%s', '%s')"
            , $conn->real_escape_string($usuario->nombre)
            , $conn->real_escape_string($usuario->email)
            , $conn->real_escape_string($usuario->password)
            , $conn->real_escape_string($usuario->rol));

        if ( !$conn->query($query) ){
            echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
            exit();
        }
        return $usuario;
    }
    
    private static function actualiza($usuario){
        $app = Aplicacion::getInstance();
        $conn = $app->conexionBD();

        $user = buscaUsuario($usuario->nombre);

        if ($user){
            $query=sprintf("UPDATE entrenador U SET Nombre = '%s', Email='%s', Password='%s', Rol='%s' WHERE U.id=%i"
            , $conn->real_escape_string($usuario->nombre)
            , $conn->real_escape_string($usuario->email)
            , $conn->real_escape_string($usuario->password)
            , $conn->real_escape_string($usuario->edad)
            , $conn->real_escape_string($usuario->rol)
            , $usuario->id);
            if ( $conn->query($query) ) {
                if ( $conn->affected_rows != 1) {
                    echo "No se ha podido actualizar el usuario: " . $usuario->id;
                    exit();
                }
            } else {
                echo "Error al actualizar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
                exit();
            }
        }
        
        
        return $usuario;
    }

    public static function nClientes($usuario){
        $app = Aplicacion::getInstance();
        $conn = $app->conexionBD();

        $query =sprintf("SELECT COUNT(id_entrenador) FROM entrenador_deportista WHERE idEntrenador='%s' "
        , $conn->real_escape_string($usuario->id()));

        var_dump($conn->query($query));
    }

    /*public static function sesiones($idPulsera){
        $usuario = self::buscaUsuario($idPulsera);

        $app = Aplicacion::getInstance();
        $conn= $app->conexionBD();
        $query = sprintf("SELECT * FROM sesión WHERE idPulsera='%s' ORDER BY Fecha DESC ", $conn->real_escape_string($usuario->idPulsera()));
        $rs = $conn->query($query);
        $rt=false;

        if ($rs){
            if($rs->num_rows>0){
                $row = mysqli_fetch_assoc($rs);
                $rt = "<div class= 'grupo-control'";
                $rt .= "<lable>Fecha: ".$row['Fecha']." Inicio: ".$row['HoraIni'];
                $rt .= "</div>";
                
                $i = 0;
                while($row = mysqli_fetch_assoc($rs)){
                    $rt .= "<div class= 'grupo-control'";
                    $rt .= "<lable>Fecha: ".$row['Fecha']." Inicio: ".$row['HoraIni'];
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

    public static function memes($nombre){
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

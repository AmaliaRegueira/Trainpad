<?php

require_once('aplicacion.php');

class Entrenador {

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

    public function nombre(){
        return $this->nombre;
    }

    public function email(){
        return $this->email;
    }

    public function rol(){ 
        return $this->rol;
     }

    public function setUserName($name){
        $this->nombre = $name;
    }

     public function cambiaPassword($nuevoPassword){
        $this->password = password_hash($nuevoPassword, PASSWORD_DEFAULT);
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
                $user = new Entrenador($fila['Nombre'], $fila['Email'], $fila['Password'], $fila['Rol']);
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
    public static function login($nombre, $password){
        $user = self::searchTrainer($nombre);
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
    
    public static function update($usuario,$nombre){
        $app = Aplicacion::getInstance();
        $conn = $app->conexionBD();

        $user = self::searchTrainer($nombre);

        if ($user){
            $query=sprintf("UPDATE entrenador U SET Nombre = '%s', Email='%s', Password='%s', Rol='%s' WHERE U.Nombre='%s'"
            , $conn->real_escape_string($usuario->nombre)
            , $conn->real_escape_string($usuario->email)
            , $conn->real_escape_string($usuario->password)
            , $conn->real_escape_string($usuario->rol)
            , $nombre);
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
        
        return true;
    }

    public static function nClientes($usuario){
        $app = Aplicacion::getInstance();
        $conn = $app->conexionBD();

        $query =sprintf("SELECT Entrenador FROM deportistas WHERE Entrenador='%s' "
        , $conn->real_escape_string($usuario->nombre()));
        
        if(!$conn->query($query)){
               echo "Error en la busqueda: (". $conn->errno . ") ". utf8_encode($conn->error);
           }
        
        $rt = $conn->query($query);
        return $rt->num_rows;

    }

    public static function clientes($usuario){
        $app = Aplicacion::getInstance();
        $conn = $app->conexionBD();

        $query =sprintf("SELECT * FROM deportistas WHERE Entrenador='%s' "
        , $conn->real_escape_string($usuario->nombre()));
        $rs = $conn->query($query);
        $rt=false;

        if ($rs){
            if($rs->num_rows>0){
                $row = mysqli_fetch_assoc($rs);

                $rt = "<div class= 'grupo'>";
                $rt .= "<h3>Nombre: </h3><label>".$row['Nombre']."</label><h3> Correo: </h3><label>".$row['Email']."</label><h3> Edad: </h3><label>".$row['Edad']."</label>";
                $rt .= "</div>";
                
                while($row = mysqli_fetch_assoc($rs)){
                    $rt .= "<div class= 'grupo'>";
                    $rt .= "<h3>Nombre: </h3><label>".$row['Nombre']."</label><h3> Correo: </h3><label>".$row['Email']."</label><h3> Edad: </h3><label>".$row['Edad']."</label>";
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

    public static function imprimeEntrenadores(){
        $app = Aplicacion::getInstance();
        $conn = $app->conexionBD();

        $query =sprintf("SELECT * FROM entrenador");
        $rs = $conn->query($query);
    
     if ($rs){
            if($rs->num_rows>0){
                $row = mysqli_fetch_assoc($rs);

                $rt = "<div class= 'grupo'>";
                $rt .= "<h3>Nombre: </h3><label>".$row['Nombre']."</label><h3> Correo: </h3><label>".$row['Email']."</label>";
                $rt .= "</div>";
                
                while($row = mysqli_fetch_assoc($rs)){
                    $rt .= "<div class= 'grupo'>";
                    $rt .= "<h3>Nombre: </h3><label>".$row['Nombre']."</label><h3> Correo: </h3><label>".$row['Email']."</label>";
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

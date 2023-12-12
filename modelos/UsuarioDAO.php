<?php

class UsuarioDAO {
    private mysqli $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Obtiene un usuario de la BD en función del id
     * @return Usuario Devuelve un Objeto de la clase Usuario o null si no existe
     */
    public function getById($id):Usuario|null {
        if(!$stmt = $this->conn->prepare("SELECT * FROM Usuarios WHERE ID = ?"))
        {
            echo "Error en la SQL: " . $this->conn->error;
        }
        //Asociar las variables a las interrogaciones(parámetros)
        $stmt->bind_param('i',$id);
        //Ejecutamos la SQL
        $stmt->execute();
        //Obtener el objeto mysql_result
        $result = $stmt->get_result();

        //Si ha encontrado algún resultado devolvemos un objeto de la clase Usuario, sino null
        if($result->num_rows >= 1){
            $usuario = $result->fetch_object(Usuario::class);
            return $usuario;
        }
        else{
            return null;
        }
    } 

     /**
     * Obtiene un usuario de la BD en función del email
     * @return Usuario Devuelve un Objeto de la clase Usuario o null si no existe
     */
    public function getByEmail($email):Usuario|null {
        if(!$stmt = $this->conn->prepare("SELECT * FROM Usuarios WHERE Email = ?"))
        {
            echo "Error en la SQL: " . $this->conn->error;
        }
        //Asociar las variables a las interrogaciones(parámetros)
        $stmt->bind_param('s',$email);
        //Ejecutamos la SQL
        $stmt->execute();
        //Obtener el objeto mysql_result
        $result = $stmt->get_result();

        //Si ha encontrado algún resultado devolvemos un objeto de la clase Usuario, sino null
        if($result->num_rows >= 1){
            $usuario = $result->fetch_object(Usuario::class);
            return $usuario;
        }
        else{
            return null;
        }
    } 
    /**
     * Obtiene todos los usuarios
     */
    public function getAll():array {
        if(!$stmt = $this->conn->prepare("SELECT * FROM Usuarios"))
        {
            echo "Error en la SQL: " . $this->conn->error;
        }
        //Ejecutamos la SQL
        $stmt->execute();
        //Obtener el objeto mysql_result
        $result = $stmt->get_result();

        $array_mensajes = array();
        
        while($usuario = $result->fetch_object(Usuario::class)){
            $array_usuarios[] = $usuario;
        }
        return $array_usuarios;
    }


    /**
     * Inserta en la base de datos el usuario que recibe como parámetro
     * @return idUsuario
     */
    function insert(Usuario $usuario): int|bool{
        if(!$stmt = $this->conn->prepare("INSERT INTO Usuarios (Email, Password, Nombre, Telefono, Poblacion) VALUES (?,?,?,?,?)")){
            die("Error al preparar la consulta insert: " . $this->conn->error );
        }
        $email = $usuario->getEmail();
        $password = $usuario->getPassword();
        $nombre= $usuario->getNombre();
        $telefono= $usuario->getTelefono();
        $poblacion=$usuario->getPoblacion();
        $stmt->bind_param('sssss', $email, $password, $nombre, $telefono, $poblacion);
        if($stmt->execute()){
            return $stmt->insert_id;
        }
        else{
            return false;
        }
    }
}
?>

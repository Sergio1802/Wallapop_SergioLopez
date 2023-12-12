<?php 
class FotosDAO {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Obtiene una foto de anuncio de la BD en función del ID
     * @return Fotos|null Devuelve un objeto de la clase Fotos o null si no existe
     */
    public function getById($id): ?Fotos {
        if (!$stmt = $this->conn->prepare("SELECT * FROM FotosAnuncios WHERE ID = ?")) {
            echo "Error en la SQL: " . $this->conn->error;
        }
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows >= 1) {
            $fotoAnuncio = $result->fetch_object(Fotos::class);
            return $fotoAnuncio;
        } else {
            return null;
        }
    }
    /**
     * Obtiene una foto de anuncio de la BD en función del ID
     * @return Fotos|null Devuelve un objeto de la clase Fotos o null si no existe
     */
    public function getImagenPrincipalById($idAnuncio): ?Fotos {
        if (!$stmt = $this->conn->prepare("SELECT * FROM FotosAnuncios WHERE EsPrincipal = 1 AND AnuncioID=?")) {
            echo "Error en la SQL: " . $this->conn->error;
        }
        $stmt->bind_param('i', $idAnuncio);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows >= 1) {
            $fotoAnuncio = $result->fetch_object(Fotos::class);
            return $fotoAnuncio;
        } else {
            return null;
        }
    }
    /**
     * Obtiene una foto de anuncio de la BD en función del ID del anuncio
     * @return Fotos|null Devuelve un objeto de la clase Fotos o null si no existe
     */
    public function getByAnuncioId($idAnuncio): ?Fotos {
        if (!$stmt = $this->conn->prepare("SELECT * FROM FotosAnuncios WHERE AnuncioID = ?")) {
            echo "Error en la SQL: " . $this->conn->error;
        }
        $stmt->bind_param('i', $idAnuncio);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows >= 1) {
            $fotoAnuncio = $result->fetch_object(Fotos::class);
            return $fotoAnuncio;
        } else {
            return null;
        }
    }

    /**
     * Obtiene todas las fotos de anuncios de la tabla FotosAnuncios para un anuncio específico
     * @return array Devuelve un array de objetos de la clase Fotos
     */
    public function getAllByAnuncioID($anuncioID): array {
        if (!$stmt = $this->conn->prepare("SELECT * FROM FotosAnuncios WHERE AnuncioID = ?")) {
            echo "Error en la SQL: " . $this->conn->error;
        }
        $stmt->bind_param('i', $anuncioID);
        $stmt->execute();
        $result = $stmt->get_result();

        $array_fotos = array();

        while ($fotoAnuncio = $result->fetch_object(Fotos::class)) {
            $array_fotos[] = $fotoAnuncio;
        }
        return $array_fotos;
    }

    /**
     * Inserta en la base de datos la foto de anuncio que recibe como parámetro
     * @return int|bool Devuelve el ID autonumérico asignado a la foto de anuncio o false en caso de error
     */
    public function insert(Fotos $fotoAnuncio): int|bool {
        if (!$stmt = $this->conn->prepare("INSERT INTO FotosAnuncios (Imagen, AnuncioID, EsPrincipal) VALUES (?,?,?)")) {
            die("Error al preparar la consulta insert: " . $this->conn->error);
        }
        $imagen = $fotoAnuncio->getImagen();
        $anuncioID = $fotoAnuncio->getAnuncioID();
        $esPrincipal = $fotoAnuncio->getPrincipal();

        $stmt->bind_param('sii', $imagen, $anuncioID, $esPrincipal);
        if ($stmt->execute()) {
            return $stmt->insert_id;
        } else {
            return false;
        }
    }

    function delete($idAnuncio): bool
    {

        if (!$stmt = $this->conn->prepare("DELETE FROM FotosAnuncios WHERE AnuncioID = ?")) {
            echo "Error en la SQL: " . $this->conn->error;
        }
        //Asociar las variables a las interrogaciones(parámetros)
        $stmt->bind_param('i', $idAnuncio);
        //Ejecutamos la SQL
        $stmt->execute();
        //Comprobamos si ha borrado algún registro o no
        return $stmt->affected_rows;
    }
    public function update(Fotos $foto): bool {
        if (!$stmt = $this->conn->prepare("UPDATE FotosAnuncios SET Imagen=?, AnuncioID=?, EsPrincipal=? WHERE ID=?")) {
            echo "Error al preparar la consulta update: " . $this->conn->error;
            return false;
        }
        
        $imagen = $foto->getImagen();
        $anuncioID = $foto->getAnuncioID();
        $esPrincipal = $foto->getPrincipal();
        $id = $foto->getId();
    
        $stmt->bind_param('siii', $imagen, $anuncioID, $esPrincipal, $id);
        if ($stmt->execute()) {
            return true;
        } else {
            echo "Error al ejecutar la consulta: " . $stmt->error;
            return false;
        }
    }
}

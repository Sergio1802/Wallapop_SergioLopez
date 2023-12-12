<?php
class AnuncioDAO
{
    private mysqli $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Obtiene un anuncio de la BD en función del ID
     * @return Anuncio|null Devuelve un objeto de la clase Anuncio o null si no existe
     */
    public function getById($id): ?Anuncio
    {
        if (!$stmt = $this->conn->prepare("SELECT * FROM Anuncios WHERE ID = ?")) {
            echo "Error en la SQL: " . $this->conn->error;
        }
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows >= 1) {
            $anuncio = $result->fetch_object(Anuncio::class);
            return $anuncio;
        } else {
            return null;
        }
    }

    /**
     * Obtiene todos los anuncios de la tabla Anuncios
     * @return array Devuelve un array de objetos de la clase Anuncio
     */
    public function getAll(): array
    {
        if (!$stmt = $this->conn->prepare("SELECT * FROM Anuncios ORDER BY FechaCreacion DESC")) {
            echo "Error en la SQL: " . $this->conn->error;
        }
        $stmt->execute();
        $result = $stmt->get_result();

        $array_anuncios = array();

        while ($anuncio = $result->fetch_object(Anuncio::class)) {
            $array_anuncios[] = $anuncio;
        }
        return $array_anuncios;
    }

    public function getAllPaginacion($pagina): array
    {
        $anunciosPorPagina = 5;
        $omitir = ($pagina - 1) * $anunciosPorPagina;

        if (!$stmt = $this->conn->prepare("SELECT * FROM Anuncios ORDER BY FechaCreacion DESC LIMIT ?, ?")) {
            echo "Error en la SQL: " . $this->conn->error;
        }

        $stmt->bind_param("ii", $omitir, $anunciosPorPagina);
        $stmt->execute();
        $result = $stmt->get_result();

        $array_anuncios = array();

        while ($anuncio = $result->fetch_object(Anuncio::class)) {
            $array_anuncios[] = $anuncio;
        }

        return $array_anuncios;
    }

    public function getAllPaginacionUser($pagina, $idUsuario): array
    {
        $anunciosPorPagina = 5;
        $omitir = ($pagina - 1) * $anunciosPorPagina;

        if (!$stmt = $this->conn->prepare("SELECT * FROM Anuncios WHERE UsuarioID = ? ORDER BY FechaCreacion DESC LIMIT ?, ?")) {
            echo "Error en la SQL: " . $this->conn->error;
        }

        $stmt->bind_param("iii", $idUsuario, $omitir, $anunciosPorPagina);
        $stmt->execute();
        $result = $stmt->get_result();

        $array_anuncios = array();

        while ($anuncio = $result->fetch_object(Anuncio::class)) {
            $array_anuncios[] = $anuncio;
        }

        return $array_anuncios;
    }

    public function getTotalAnuncios(): int
    {
        if (!$stmt = $this->conn->prepare("SELECT COUNT(*) AS total FROM Anuncios")) {
            die("Error en la SQL: " . $this->conn->error);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    /**
     * Obtiene todos los anuncios de la tabla Anuncios según el usuario
     * @return array Devuelve un array de objetos de la clase Anuncio
     */
    public function getAllByUser($idUsuario): array
    {
        if (!$stmt = $this->conn->prepare("SELECT * FROM Anuncios WHERE UsuarioID = ? ORDER BY FechaCreacion DESC")) {
            echo "Error en la SQL: " . $this->conn->error;
        }

        $array_anuncios = array();
        $stmt->bind_param('i', $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows >= 1) {
            while ($anuncio = $result->fetch_object(Anuncio::class)) {
                $array_anuncios[] = $anuncio;
            }
        }

        return $array_anuncios;
    }

    /**
     * Inserta en la base de datos el anuncio que recibe como parámetro
     * @return int|bool Devuelve el ID autonumérico asignado al anuncio o false en caso de error
     */
    public function insert(Anuncio $anuncio): int|bool
    {
        if (!$stmt = $this->conn->prepare("INSERT INTO Anuncios (Titulo, Descripcion, Precio, UsuarioID) VALUES (?,?,?,?)")) {
            die("Error al preparar la consulta insert: " . $this->conn->error);
        }
        $titulo = $anuncio->getTitulo();
        $descripcion = $anuncio->getDescripcion();
        $precio = $anuncio->getPrecio();
        $usuarioID = $anuncio->getUsuarioID();

        $stmt->bind_param('ssdi', $titulo, $descripcion, $precio, $usuarioID);

        if ($stmt->execute()) {
            return $stmt->insert_id;
        } else {
            return false;
        }
    }

    public function getLastInsertedId(): int|bool
    {
        return $this->conn->insert_id;
    }

    public function update($anuncio)
    {
        if (!$stmt = $this->conn->prepare("UPDATE Anuncios SET Titulo=?, Descripcion=? WHERE ID=?")) {
            die("Error al preparar la consulta update: " . $this->conn->error);
        }
        $titulo = $anuncio->getTitulo();
        $descripcion = $anuncio->getDescripcion();
        $id = $anuncio->getId();
        $stmt->bind_param('ssi', $titulo, $descripcion, $id);
        return $stmt->execute();
    }

    function delete($id): bool
    {

        if (!$stmt = $this->conn->prepare("DELETE FROM Anuncios WHERE id = ?")) {
            echo "Error en la SQL: " . $this->conn->error;
        }
        //Asociar las variables a las interrogaciones(parámetros)
        $stmt->bind_param('i', $id);
        //Ejecutamos la SQL
        $stmt->execute();
        //Comprobamos si ha borrado algún registro o no
        return $stmt->affected_rows;
    }
}

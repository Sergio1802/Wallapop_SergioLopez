<?php 

class Anuncio{
    private $ID;
    private $Titulo;
    private $Descripcion;
    private $Precio;
    private $FechaCreacion;
    private $UsuarioID;

    /**
     * Get the value of ID
     */
    public function getId()
    {
        return $this->ID;
    }

    /**
     * Set the value of ID
     */
    public function setId($ID): self
    {
        $this->ID = $ID;

        return $this;
    }

    /**
     * Get the value of Titulo
     */
    public function getTitulo()
    {
        return $this->Titulo;
    }

    /**
     * Set the value of Titulo
     */
    public function setTitulo($Titulo): self
    {
        $this->Titulo = $Titulo;

        return $this;
    }

    /**
     * Get the value of Descripcion
     */
    public function getDescripcion()
    {
        return $this->Descripcion;
    }

    /**
     * Set the value of Descripcion
     */
    public function setDescripcion($Descripcion): self
    {
        $this->Descripcion = $Descripcion;

        return $this;
    }

    /**
     * Get the value of Precio
     */
    public function getPrecio()
    {
        return $this->Precio;
    }

    /**
     * Set the value of Precio
     */
    public function setPrecio($Precio): self
    {
        $this->Precio = $Precio;

        return $this;
    }

    /**
     * Get the value of FechaCreacion
     */
    public function getFechaCreacion()
    {
        return $this->FechaCreacion;
    }

    /**
     * Set the value of FechaCreacion
     */
    public function setFechaCreacion($FechaCreacion): self
    {
        $this->FechaCreacion = $FechaCreacion;

        return $this;
    }

    /**
     * Get the value of UsuarioID
     */
    public function getUsuarioId()
    {
        return $this->UsuarioID;
    }

    /**
     * Set the value of UsuarioID
     */
    public function setUsuarioId($UsuarioID): self
    {
        $this->UsuarioID = $UsuarioID;

        return $this;
    }
}
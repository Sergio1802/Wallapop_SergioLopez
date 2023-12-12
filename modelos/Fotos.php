<?php 

class Fotos{

    private $ID;
    private $Imagen;
    private $AnuncioID;
    private $EsPrincipal;

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
     * Get the value of Imagen
     */
    public function getImagen()
    {
        return $this->Imagen;
    }

    /**
     * Set the value of Imagen
     */
    public function setImagen($Imagen): self
    {
        $this->Imagen = $Imagen;

        return $this;
    }

    /**
     * Get the value of AnuncioID
     */
    public function getAnuncioID()
    {
        return $this->AnuncioID;
    }

    /**
     * Set the value of AnuncioID
     */
    public function setAnuncioID($AnuncioID): self
    {
        $this->AnuncioID = $AnuncioID;

        return $this;
    }

    /**
     * Get the value of EsPrincipal
     */
    public function getPrincipal()
    {
        return $this->EsPrincipal;
    }

    /**
     * Set the value of EsPrincipal
     */
    public function setPrincipal($EsPrincipal): self
    {
        $this->EsPrincipal = $EsPrincipal;

        return $this;
    }
}
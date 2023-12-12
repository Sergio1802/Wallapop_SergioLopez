<?php 

class Usuario{
    private $ID;
    private $Email;
    private $Password;
    private $Nombre;
    private $Telefono;
    private $Poblacion;

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
     * Get the value of Email
     */
    public function getEmail()
    {
        return $this->Email;
    }

    /**
     * Set the value of Email
     */
    public function setEmail($Email): self
    {
        $this->Email = $Email;

        return $this;
    }

    /**
     * Get the value of Password
     */
    public function getPassword()
    {
        return $this->Password;
    }

    /**
     * Set the value of Password
     */
    public function setPassword($Password): self
    {
        $this->Password = $Password;

        return $this;
    }

    /**
     * Get the value of Nombre
     */
    public function getNombre()
    {
        return $this->Nombre;
    }

    /**
     * Set the value of Nombre
     */
    public function setNombre($Nombre): self
    {
        $this->Nombre = $Nombre;

        return $this;
    }

    /**
     * Get the value of Telefono
     */
    public function getTelefono()
    {
        return $this->Telefono;
    }

    /**
     * Set the value of Telefono
     */
    public function setTelefono($Telefono): self
    {
        $this->Telefono = $Telefono;

        return $this;
    }

    /**
     * Get the value of Poblacion
     */
    public function getPoblacion()
    {
        return $this->Poblacion;
    }

    /**
     * Set the value of Poblacion
     */
    public function setPoblacion($Poblacion): self
    {
        $this->Poblacion = $Poblacion;

        return $this;
    }
}
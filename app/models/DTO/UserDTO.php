<?php

class UserDTO implements JsonSerializable{
    private $dni;
    private $name;
    private $surname;
    private $departmentName;

    public function __construct($dni, $name, $surname, $departmentName)
    {
        $this->dni = $dni;
        $this->name = $name;
        $this->surname = $surname;
        $this->departmentName = $departmentName;
    }

    /**
     * Get the value of dni
     */
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the value of surname
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Get the value of departmentName
     */
    public function getDepartmentName()
    {
        return $this->departmentName;
    }

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }

}
<?php

/** He creado el modelo User.
 * Aquí defino el modelo con su tipo de dato e importo el fichero JSON
 **/










class User
{


    private string $name;
    private string $surname;
    private string $dni;
    private DateTime $dateOfBirth;
    private string $departmentId;

    // Constructor para inicializar propiedades

    public function __construct(string $name, string $surname, string $dni, DateTime $dateOfBirth, string $departmentId)
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->dni = $dni;
        $this->dateOfBirth = $dateOfBirth;
        $this->departmentId = $departmentId;
    }

    // Getters
    public function getName()
    {
        return $this->name;
    }
    public function getSurname()
    {
        return $this->surname;
    }
    public function getDni()
    {
        return $this->dni;
    }
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }
    public function getDepartmentId()
    {
        return $this->departmentId;
    }

    // Setters
    public function setName(string $name): void {
        // if (empty($name)) throw new Exception("El nombre es obligatorio");
        $this->name = ucfirst($name);
    }

    public function setSurname(string $surname): void {
        // if (empty($surname)) throw new Exception("El apellido es obligatorio");
        $this->surname = ucfirst($surname);
    }

    public function setDni(string $dni): void {
        // if (empty($dni)) throw new Exception("El DNI es obligatorio");
        // if (!validarDNI($dni)) throw new Exception("El DNI no es válido");
        $this->dni = $dni;
    }

    public function setDateOfBirth(string $dateOfBirth): void {
        // if (empty($dateOfBirth)) throw new Exception("La fecha de nacimiento es obligatoria");
        $this->dateOfBirth = $dateOfBirth;
    }

    public function setDepartmentId(string $departmentId): void {
        // if (empty($departmentId)) throw new Exception("El departamento es obligatorio");
        $this->departmentId = ucfirst($departmentId);
    }


    /*********** Funciones necesarias ***********/
    
    function validacionesDeUsuario()
    {
        // Valido los datos insertados en body (formulario) y voy completando el array $arrayErrores con los errores que aparezcan
        $arrayErrores = array();
        if (empty($this->name)) {
            $arrayErrores["name"] = 'El nombre es obligatorio';
        }
        if (empty($this->surname)) {
            $arrayErrores["surname"] = 'El apellido es obligatorio';
        }
        if (empty($this->dni)) {
            $arrayErrores["dni"] = 'El DNI es obligatorio';
        } elseif (!validarDNI($this->dni)) {
            $arrayErrores["dni"] = 'El DNI no es válido';
        }
        if (empty($this->dateOfBirth)) {
            $arrayErrores["dateOfBirth"] = 'La fecha de nacimiento es obligatoria';
        }
        if (empty($this->departmentId)) {
            $arrayErrores["departmentId"] = 'El departamento es obligatorio';
        }
        return $arrayErrores;
    }
}


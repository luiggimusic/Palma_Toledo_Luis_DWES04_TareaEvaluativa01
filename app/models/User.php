<?php
class User
{
    private string $name;
    private string $surname;
    private string $dni;
    private string $dateOfBirth;
    private string $departmentId;

    public function __construct(string $name, string $surname, string $dni, string $dateOfBirth, string $departmentId)
    {

        $this->name = $name;
        $this->surname = $surname;
        $this->dni = $dni;
        $this->dateOfBirth = formatDate($dateOfBirth);
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
    public function setName(string $name): void
    {
        $this->name = ucfirst($name);
    }

    public function setSurname(string $surname): void
    {
        $this->surname = ucfirst($surname);
    }

    public function setDni(string $dni): void
    {
        $this->dni = $dni;
    }

    public function setDateOfBirth(string $dateOfBirth): void
    {
        $this->dateOfBirth = $dateOfBirth;
    }

    public function setDepartmentId(string $departmentId): void
    {
        $this->departmentId = strtoupper($departmentId);
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

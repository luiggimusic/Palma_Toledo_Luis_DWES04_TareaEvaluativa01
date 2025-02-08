<?php

class DepartmentEntity
{
    private string $departmentId;
    private string $departmentName;

    // Constructor para inicializar propiedades

    public function __construct(string $departmentId, string $departmentName)
    {
        $this->departmentId = $departmentId;
        $this->departmentName = $departmentName;
    }

    /**
     * Get the value of departmenId
     */
    public function getDepartmentId(): string
    {
        return $this->departmentId;
    }

    /**
     * Set the value of departmenId
     */
    public function setDepartmentId(string $departmentId): self
    {
        $this->departmentId = $departmentId;

        return $this;
    }

    /**
     * Get the value of departmentName
     */
    public function getDepartmentName(): string
    {
        return $this->departmentName;
    }

    /**
     * Set the value of departmentName
     */
    public function setDepartmentName(string $departmentName): self
    {
        $this->departmentName = $departmentName;

        return $this;
    }

    /*********** Funciones necesarias ***********/
    function validacionesDeDepartment()
    {
        // Valido los datos insertados en body (formulario) y voy completando el array $arrayErrores con los errores que aparezcan
        $arrayErrores = array();
        if (empty($this->departmentId)) {
            $arrayErrores["departmentId"] = 'El ID del departamento es obligatorio';
        }
        if (empty($this->departmentName)) {
            $arrayErrores["departmentName"] = 'El nombre del departamento es obligatorio';
        }
        return $arrayErrores;
    }
}

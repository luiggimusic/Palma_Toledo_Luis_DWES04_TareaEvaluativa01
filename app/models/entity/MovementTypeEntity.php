<?php

class MovementTypeEntity
{
    private string $movementTypeId;
    private string $movementTypeName;

    // Constructor para inicializar propiedades

    public function __construct(string $movementTypeId, string $movementTypeName)
    {
        $this->movementTypeId = $movementTypeId;
        $this->movementTypeName = $movementTypeName;
    }

    /**
     * Get the value of movementId
     */
    public function getMovementTypeId(): string
    {
        return $this->movementTypeId;
    }

    /**
     * Set the value of movementTypeId
     */
    public function setMovementTypeId(string $movementTypeId): self
    {
        $this->movementTypeId = strtoupper($movementTypeId);

        return $this;
    }

    /**
     * Get the value of movementTypeName
     */
    public function getMovementTypeName(): string
    {
        return $this->movementTypeName;
    }

    /**
     * Set the value of movementTypeName
     */
    public function setMovementTypeName(string $movementTypeName): self
    {
        $this->movementTypeName = ucfirst($movementTypeName);

        return $this;
    }

    /*********** Funciones necesarias ***********/
    function validacionesDeMovementType()
    {
        // Valido los datos insertados en body (formulario) y voy completando el array $arrayErrores con los errores que aparezcan
        $arrayErrores = array();
        if (empty($this->movementTypeId)) {
            $arrayErrores["movementTypeId"] = 'El ID del tipo de movimiento es obligatorio';
        }
        if (empty($this->movementTypeName)) {
            $arrayErrores["movementTypeName"] = 'El nombre del tipo de movimiento es obligatorio';
        }
        return $arrayErrores;
    }    
}

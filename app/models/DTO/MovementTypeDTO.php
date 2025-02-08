<?php

class MovementTypeDTO implements JsonSerializable{
    private string $movementTypeId;
    private string $movementTypeName;

    public function __construct(string $movementTypeId, string $movementTypeName)
    {
        $this->movementTypeId = $movementTypeId;
        $this->movementTypeName = $movementTypeName;
    }

    // Getters
    public function getMovementTypeId()
    {
        return $this->movementTypeId;
    }
    public function getMovementTypeName()
    {
        return $this->movementTypeName;
    }

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    } 
}
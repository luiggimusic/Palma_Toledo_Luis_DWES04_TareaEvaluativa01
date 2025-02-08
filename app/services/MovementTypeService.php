<?php
class MovementTypeService {
    public function createMovementTypeObject($data) {
        // Creo instancia del modelo
        $movementType = new MovementTypeEntity("","");

        // Uso los setters para actualizar los datos
        if (isset($data['movementTypeId'])) {
            $movementType->setMovementTypeId($data['movementTypeId']);
        }
        if (isset($data['movementTypeName'])) {
            $movementType->setMovementTypeName($data['movementTypeName']);
        }

        // Devuelvo el objeto
        return $movementType;
    }
}

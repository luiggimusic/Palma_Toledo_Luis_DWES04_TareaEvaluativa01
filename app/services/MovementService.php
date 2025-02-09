<?php
class MovementService {
    public function createMovementObject($data) {
        // Creo instancia del modelo
        $movement = new MovementEntity("","","","","",0,"","01/01/1900","","");

        // Uso los setters para actualizar los datos
        if (isset($data['productCode'])) {
            $movement->setProductCode($data['productCode']);
        }
        if (isset($data['fromBatchNumber'])) {
            $movement->setFromBatchNumber($data['fromBatchNumber']);
        }
        if (isset($data['toBatchNumber'])) {
            $movement->setToBatchNumber($data['toBatchNumber']);
        }
        if (isset($data['fromLocation'])) {
            $movement->setFromLocation($data['fromLocation']);
        }
        if (isset($data['toLocation'])) {
            $movement->setToLocation($data['toLocation']);
        }
        if (isset($data['quantity'])) {
            $movement->setQuantity($data['quantity']);
        }
        if (isset($data['movementTypeId'])) {
            $movement->setMovementTypeId($data['movementTypeId']);
        }
        if (isset($data['movementDate'])) {
            $movement->setMovementDate(formatDate($data['movementDate']));
        }
        if (isset($data['customer'])) {
            $movement->setCustomer($data['customer']);
        }
        if (isset($data['supplier'])) {
            $movement->setSupplier($data['supplier']);
        }
        // Devuelvo el objeto
        return $movement;
    }
}

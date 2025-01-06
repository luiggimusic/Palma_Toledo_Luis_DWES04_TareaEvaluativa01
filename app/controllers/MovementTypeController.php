<?php

declare(strict_types=1);

require_once '../app/models/MovementType.php'; // cargo el modelo
require_once '../app/helpers/arrayHelper.php'; // cargo el fichero con las funciones que me permitirán trabajar con los arrays

class MovementTypeController
{
    function __construct() {}

    // GET
    function getAllMovementTypes()
    {
        $dataArray = MovementType::getAll();
        print_r($dataArray);
    }

    function getMovementTypeById($id)
    {
        $success = MovementType::getById($id);
        if ($success) {
            echo "Status Code: 200 OK\nRegistro encontrado \n";
            print_r($success);
        } else {
            echo "Status Code: 409 Conflict\nNo se ha encontrado el tipo de moviento";
        }
    }

    // POST
    function createMovementType($data)
    {
        $movementTypeData = [
            'movementId' => $data["movementId"],
            'movementName' => $data["movementName"],
        ];

        // Llamo al método estático "create"
        $success = MovementType::create($movementTypeData);

        if ($success) {
            echo "Status Code: 201 OK\nTipo de movimiento creado correctamente";
        } else {
            echo "Status Code: 409 Conflict\nNo se ha creado el tipo de movimiento";
        }
    }

    // PUT
    function updateMovementType($id, $data)
    {
        $movementTypeData = [
            'id' => $data["id"],
            'movementId' => $data["movementId"],
            'movementName' => $data["movementName"],
        ];
        // Llamo al método estático "update" para actualizar
        $success = MovementType::update($id, $data);

        if ($success) {
            echo "Status Code: 204 OK\nTipo de movimiento actualizado correctamente";
        } else {
            echo "Status Code: 409 Conflict\nError al actualizar";
        }
    }
    function deleteMovementType($id)
    {
        $success = MovementType::delete($id);

        if ($success) {
            echo "Status Code: 204 OK\nTipo de movimiento eliminado"; 
        } 
        else{
            echo "Status Code: 409 Conflict\nError al eliminar";
        }
    }
}

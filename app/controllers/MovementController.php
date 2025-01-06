<?php

declare(strict_types=1);
header('Content-Type: application/json'); // le indico al cliente que la respuesta es de tipo JSON.
require_once '../app/models/Movement.php'; // cargo el modelo
require_once '../app/helpers/arrayHelper.php'; // cargo el fichero con las funciones que me permitirán trabajar con los arrays

class MovementController
{
    function __construct() {}

    // GET
    function getAllMovements()
    {
        $dataArray = Movement::getAll();
        print_r($dataArray);
    }

    function getMovementById($id)
    {
        $success = Movement::getById($id);
        if ($success) {
            echo "Status Code: 200 OK\nRegistro encontrado \n";
            print_r($success);
        } else {
            echo "Status Code: 409 Conflict\nNo se ha encontrado la categoría";
        }
    }

    function getMovementByData($data)
    {
        $movementData = [
            'productCode' => $data['productCode'],
            // 'productName' => $data["productName"],
            'fromBatchNumber' => $data["fromBatchNumber"],
            'toBatchNumber' => $data["toBatchNumber"],
            'fromLocation' => $data["fromLocation"],
            'toLocation' => $data["toLocation"],
            'quantity' => $data["quantity"],
            'movementId' => $data["movementId"],
            'movementDate' => $data["movementDate"],
            'customer' => $data["customer"],
            'supplier' => $data["supplier"],
        ];

        $success = Movement::getByData($movementData);
        if ($success) {
            echo "Status Code: 200 OK\nMovimientos encontrados \n";
            print_r($success);
        } else {
            echo "Status Code: 409 Conflict\nNo se han encontrado movimientos";
        }
    }

    // POST
    function sale($data)
    {
        $movementData = [
            'productCode' => $data["productCode"],
            'productName' => $data["productName"],
            'fromBatchNumber' => $data["fromBatchNumber"],
            'toBatchNumber' => $data["toBatchNumber"],
            'fromLocation' => $data["fromLocation"],
            'toLocation' => $data["toLocation"],
            'quantity' => $data["quantity"],
            'movementId' => $data["movementId"],
            'movementDate' => $data["movementDate"],
            'customer' => $data["customer"],
            'supplier' => $data["supplier"],
        ];

        // Llamo al método estático "create"
        $success = Movement::sale($movementData);

        if ($success) {
            echo "Status Code: 201 OK\nMovimiento registrado correctamente";
        } else {
            echo "Status Code: 409 Conflict\nNo se ha registrado el movimiento";
        }
    }

    function purchase($data)
    {
        $movementData = [
            'productCode' => $data["productCode"],
            'productName' => $data["productName"],
            'fromBatchNumber' => $data["fromBatchNumber"],
            'toBatchNumber' => $data["toBatchNumber"],
            'fromLocation' => $data["fromLocation"],
            'toLocation' => $data["toLocation"],
            'quantity' => $data["quantity"],
            'movementId' => $data["movementId"],
            'movementDate' => $data["movementDate"],
            'customer' => $data["customer"],
            'supplier' => $data["supplier"],
        ];

        // Llamo al método estático "purchase"
        $success = Movement::purchase($movementData);

        if ($success) {
            echo "Status Code: 201 OK\nMovimiento registrado correctamente";
        } else {
            echo "Status Code: 409 Conflict\nNo se ha registrado el movimiento";
        }
    }

    function inventoryTransfer($data)
    {
        $movementData = [
            'productCode' => $data["productCode"],
            'productName' => $data["productName"],
            'fromBatchNumber' => $data["fromBatchNumber"],
            'toBatchNumber' => $data["toBatchNumber"],
            'fromLocation' => $data["fromLocation"],
            'toLocation' => $data["toLocation"],
            'quantity' => $data["quantity"],
            'movementId' => $data["movementId"],
            'movementDate' => $data["movementDate"],
            'customer' => $data["customer"],
            'supplier' => $data["supplier"],
        ];

        // Llamo al método estático "create"
        $success = Movement::inventoryTransfer($movementData);

        if ($success) {
            echo "Status Code: 201 OK\nMovimiento registrado correctamente";
        } else {
            echo "Status Code: 409 Conflict\nNo se ha registrado el movimiento";
        }
    }
}

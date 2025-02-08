<?php

declare(strict_types=1);
header('Content-Type: application/json'); // le indico al cliente que la respuesta es de tipo JSON.

require_once '../app/models/DAO/MovementTypeDAO.php';
require_once '../app/helpers/helper.php';

class MovementTypeController
{
    private $MovementTypeDAO;

    function __construct() {
        $this->MovementTypeDAO = new MovementTypeDAO();
    }

    // GET
    function getAllMovementTypes()
    {
        $movementTypes = $this->MovementTypeDAO->getAllMovementTypes();

        if (isset($movementTypes)) {
            return sendJsonResponse(new ApiResponse(
                status: 'success',
                code: 200,
                message: 'Datos cargados correctamente',
                data: $movementTypes
            ));
        } else {
            return sendJsonResponse(new ApiResponse(
                status: 'not success',
                code: 500,
                message: 'Error al leer los datos',
                data: $movementTypes
            ));
        }
        $movementTypes = json_encode($movementTypes);
        print_r($movementTypes);
    }

    function getMovementTypeById($id)
    {
        $movementTypes = $this->MovementTypeDAO->getMovementTypeById($id);

        if (isset($movementTypes)) {
            return sendJsonResponse(new ApiResponse(
                status: 'success',
                code: 200,
                message: 'Datos cargados correctamente',
                data: $movementTypes
            ));
        } else {
            return sendJsonResponse(new ApiResponse(
                status: 'not success',
                code: 500,
                message: 'ID del tipo de movimiento no encontrado',
                data: $movementTypes
            ));
        }
        $movementTypes = json_encode($movementTypes);
        print_r($movementTypes);
    }

    // POST
    function createMovementType($data)
    {
        $movementType = $this->MovementTypeDAO->createMovementType($data);

        if (isset($movementType)) {
            return sendJsonResponse(new ApiResponse(
                status: 'success',
                code: 200,
                message: 'Datos cargados correctamente',
                data: $movementType
            ));
        }
    }

    // PUT
    function updateMovementType($data)
    {
        $movementType = $this->MovementTypeDAO->updateMovementType($data);

        if (isset($movementType)) {
            return sendJsonResponse(new ApiResponse(
                status: 'success',
                code: 200,
                message: 'Datos cargados correctamente',
                data: $movementType
            ));
        }
    }
    function deleteMovementType($data)
    {
        $movementType = $this->MovementTypeDAO->deleteMovementType($data);

        if (isset($movementType)) {
            return sendJsonResponse(new ApiResponse(
                status: 'success',
                code: 200,
                message: 'Tipo de movimiento eliminado correctamente',
                data: $movementType
            ));
        }
    }
}

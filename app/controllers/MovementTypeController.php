<?php
declare(strict_types=1);

header('Content-Type: application/json'); // le indico al cliente que la respuesta es de tipo JSON.
require_once '../app/models/DAO/MovementTypeDAO.php';
require_once '../app/helpers/helper.php';

class MovementTypeController
{
    private $MovementTypeDAO;
    private $input; // Capturo el cuerpo de la solicitud

    function __construct() {
        $this->MovementTypeDAO = new MovementTypeDAO();
        $this->input = json_decode(file_get_contents('php://input'),true);

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
    function createMovementType()
    {
        $movementType = $this->MovementTypeDAO->createMovementType($this->input);

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
    function updateMovementType()
    {
        $movementType = $this->MovementTypeDAO->updateMovementType($this->input);

        if (isset($movementType)) {
            return sendJsonResponse(new ApiResponse(
                status: 'success',
                code: 200,
                message: 'Datos cargados correctamente',
                data: $movementType
            ));
        }
    }
    function deleteMovementType()
    {
        $movementType = $this->MovementTypeDAO->deleteMovementType($this->input);

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

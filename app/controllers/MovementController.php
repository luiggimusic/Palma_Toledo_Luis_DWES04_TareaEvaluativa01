<?php
declare(strict_types=1);

header('Content-Type: application/json'); // le indico al cliente que la respuesta es de tipo JSON.
require_once '../app/models/DAO/MovementDAO.php';
require_once '../app/helpers/helper.php'; // cargo el fichero con las funciones que me permitirán trabajar con los arrays

class MovementController
{
    private $MovementDAO;
    function __construct()
    {
        $this->MovementDAO = new MovementDAO();
    }
    // GET
    function getAllMovements()
    {
        $movements = $this->MovementDAO->getAllMovements();

        if (isset($movements)) {
            return sendJsonResponse(new ApiResponse(
                status: 'success',
                code: 200,
                message: 'Datos cargados correctamente',
                data: $movements
            ));
        } else {
            return sendJsonResponse(new ApiResponse(
                status: 'not success',
                code: 500,
                message: 'Error al leer los datos',
                data: $movements
            ));
        }
        $movements = json_encode($movements);
        print_r($movements);
    }

    function getMovementById($id) {
        $movement = $this->MovementDAO->getMovementById($id);

        if (isset($movement)) {
            return sendJsonResponse(new ApiResponse(
                status: 'success',
                code: 200,
                message: 'Datos cargados correctamente',
                data: $movement
            ));
        } else {
            return sendJsonResponse(new ApiResponse(
                status: 'not success',
                code: 500,
                message: 'ID de movimiento no encontrado',
                data: $movement
            ));
        }
        $movement = json_encode($movement);
        print_r($movement);        
    }

    function getMovementByData($data) {
        $movement = $this->MovementDAO->getMovementByData($data);

        if (isset($movement)) {
            return sendJsonResponse(new ApiResponse(
                status: 'success',
                code: 200,
                message: 'Datos cargados correctamente',
                data: $movement
            ));
        } else {
            return sendJsonResponse(new ApiResponse(
                status: 'not success',
                code: 500,
                message: 'Movimiento no encontrado con los criterios de búsqueda',
                data: $movement
            ));
        }
        $movement = json_encode($movement);
        print_r($movement);   
    }

    // POST
    function sale($data) {
        $movement = $this->MovementDAO->sale($data);

        if (isset($movement)) {
            return sendJsonResponse(new ApiResponse(
                status: 'success',
                code: 200,
                message: 'Datos cargados correctamente',
                data: $movement
            ));
        }       
    }

    function purchase($data) {}

    function inventoryTransfer($data) {}
}

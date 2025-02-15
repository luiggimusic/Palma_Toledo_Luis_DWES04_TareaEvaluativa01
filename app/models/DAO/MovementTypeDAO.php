<?php
require_once '../app/models/DTO/MovementTypeDTO.php';
require_once '../app/models/entity/MovementTypeEntity.php';
require_once '../app/services/MovementTypeService.php';

class MovementTypeDAO
{
    private $db;
    public function __construct()
    {
        $this->db = DatabaseSingleton::getInstance();
    }

    // CRUD
    // GET
    function getAllMovementTypes()
    {
        $connection = $this->db->getConnection();
        $query = "SELECT * FROM movementTypes";
        $statement = $connection->query($query);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        $movementTypeDTO = [];
        foreach ($result as $movementType) {
            $movementTypeDTO[] = new movementTypeDTO(
                $movementType['movementTypeId'],
                $movementType['movementTypeName'],
            );
        }
        if ($movementTypeDTO == null) {
            return null;
        } else {
            return $movementTypeDTO;
        }
    }

    function getMovementTypeById($id)
    {
        $connection = $this->db->getConnection();
        $query = "SELECT * FROM movementTypes WHERE id = $id";
        $statement = $connection->query($query);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        $movementTypeDTO = [];
        foreach ($result as $movementType) {
            $movementTypeDTO[] = new movementTypeDTO(
                $movementType['movementTypeId'],
                $movementType['movementTypeName'],
            );
        }
        if ($movementTypeDTO == null) {
            return null;
        } else {
            return $movementTypeDTO;
        }
    }

    // POST
    function createMovementType($data)
    {
        // Creo un objeto que me permitirá dar la respuesta sin duplicar código
        $movementTypeService = new movementTypeService();
        $movementType = $movementTypeService->createmovementTypeObject($data);

        // Valido los datos antes de la inserción
        $errores = $movementType->validacionesDemovementType();

        $connection = $this->db->getConnection();

        // Si el movementTypeId ya existe, añadirá el mensaje de error
        if (movementTypeIdVerify($connection, $data)) {
            $errores["movementTypeId"] = 'El ID del tipo de movimiento ya está registrado en el sistema';
        }        

        if (empty($errores)) {
            $query = "INSERT INTO movementTypes (movementTypeId, movementTypeName) 
                  VALUES (:movementTypeId, :movementTypeName)";
            $statement = $connection->prepare($query);
            $statement->execute([
                'movementTypeId' => $movementType->getMovementTypeId(),
                'movementTypeName' => $movementType->getMovementTypeName(),
            ]);

            // Obtengo los datos para mostrarlo en la respuesta
            $movementType = Self::showMovementTypeData($connection, $data);
            return $movementType;
        } else {
            sendJsonResponse(new ApiResponse(
                status: 'error',
                code: 400,
                message: $errores,
                data: null
            ));
            return null;
        }
    }

    // PUT
    function updateMovementType($data)
    {
        $movementTypeService = new movementTypeService();
        $movementType = $movementTypeService->createMovementTypeObject($data);

        // Valido los datos antes de la inserción
        $errores = $movementType->validacionesDemovementType();
        $connection = $this->db->getConnection();

        // Si el movementTypeId no existe, añadirá el mensaje de error
        if (!movementTypeIdVerify($connection, $data)) {
            $errores["movementTypeId"] = 'El ID del tipo de movimiento no está registrado en el sistema';
        }            

        if (empty($errores)) {
            $query = "UPDATE movementTypes SET movementTypeId=:movementTypeId, movementTypeName=:movementTypeName WHERE movementTypeId=:movementTypeId";
            $statement = $connection->prepare($query);
            $statement->execute([
                'movementTypeId' => $movementType->getMovementTypeId(),
                'movementTypeName' => $movementType->getMovementTypeName(),
            ]);

            // Obtengo los datos del departamento para mostrarlos en la respuesta
            $movementType = Self::showMovementTypeData($connection, $data);
            return $movementType;
        } else {
            sendJsonResponse(new ApiResponse(
                status: 'error',
                code: 400,
                message: $errores,
                data: null
            ));
            return null;
        }
    }

    // DELETE
    function deleteMovementType($data)
    {
        $connection = $this->db->getConnection();

        // Obtengo los datos para mostrarlo en la respuesta, 
        // en este caso antes de eliminar la tupla
        $movementType = Self::showmovementTypeData($connection, $data);

         // Verifico si el movementTypeId está en uso en otra tabla
        if (movementTypeIdInUseVerify($connection, $data)){
            $errores["movementTypeId"] = 'El ID del tipo de movimiento está relacionado a movimientos registrados';
        }        

         // Si el movementTypeId no existe, añadirá el mensaje de error
         if (!movementTypeIdVerify($connection, $data)) {
            $errores["movementTypeId"] = 'El ID del tipo de movimiento no está registrado en el sistema';
        }   
        if (empty($errores)) {
            $query = "DELETE FROM movementTypes WHERE movementTypeId=:movementTypeId";
            $statement = $connection->prepare($query);
            $statement->bindParam(':movementTypeId', $data['movementTypeId'], PDO::PARAM_STR);
            $statement->execute();

            // Una vez ejecutada la eliminación, envío los datos del elemento eliminado para que se vean en la respuesta
            return $movementType;
        } else {
            sendJsonResponse(new ApiResponse(
                status: 'error',
                code: 400,
                message: $errores,
                data: null
            ));
            return null;
        }
    }

    private function showmovementTypeData($connection, $data)
    {
        // Obtengo los datos del departamento actualizado para mostrarlo en la respuesta
        $query = "SELECT * FROM movementTypes WHERE movementTypeId = :movementTypeId";
        $statement = $connection->prepare($query);
        $statement->execute(['movementTypeId' => $data['movementTypeId']]);
        $movementTypeData = $statement->fetch(PDO::FETCH_ASSOC);
        return $movementTypeData;
    }

}

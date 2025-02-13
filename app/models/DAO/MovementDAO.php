<?php
require '../app/models/DTO/MovementDTO.php';
require '../app/models/entity/MovementEntity.php';
require '../app/services/MovementService.php';

class MovementDAO
{
    private $db;
    public function __construct()
    {
        $this->db = DatabaseSingleton::getInstance();
    }

    // CRUD
    // GET
    function getAllMovements()
    {
        $connection = $this->db->getConnection();
        $query = "SELECT * FROM movements m JOIN products p ON m.productCode=p.productCode JOIN movementTypes mt on m.movementTypeId = mt.movementTypeId";
        $statement = $connection->query($query);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        $movementsDTO = [];
        foreach ($result as $movement) {
            $movementsDTO[] = new movementDTO(
                $movement['productCode'],
                $movement['productName'],
                $movement['fromBatchNumber'],
                $movement['toBatchNumber'],
                $movement['fromLocation'],
                $movement['toLocation'],
                $movement['quantity'],
                $movement['movementTypeId'],
                $movement['movementTypeName'],
                $movement['movementDate'],
                $movement['customer'],
                $movement['supplier'],
            );
        }
        if ($movementsDTO == null) {
            return null;
        } else {
            return $movementsDTO;
        }
    }

    function getMovementById($id)
    {
        $connection = $this->db->getConnection();
        $query = "SELECT * FROM movements m JOIN products p ON m.productCode=p.productCode 
        JOIN movementTypes mt ON m.movementTypeId = mt.movementTypeId WHERE m.id = $id";
        $statement = $connection->query($query);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        $movementsDTO = [];
        foreach ($result as $movement) {
            $movementsDTO[] = new movementDTO(
                $movement['productCode'],
                $movement['productName'],
                $movement['fromBatchNumber'],
                $movement['toBatchNumber'],
                $movement['fromLocation'],
                $movement['toLocation'],
                $movement['quantity'],
                $movement['movementTypeId'],
                $movement['movementTypeName'],
                $movement['movementDate'],
                $movement['customer'],
                $movement['supplier'],
            );
        }
        if ($movementsDTO == null) {
            return null;
        } else {
            return $movementsDTO;
        }
    }

    function getMovementByData($data)
    {
        $connection = $this->db->getConnection();

        // Defino los criterios de búsqueda
        $criteria = [
            'productCode' => $data['productCode'] ?? null,
            'productName' => $data['productName'] ?? null,
            'fromBatchNumber' => $data['fromBatchNumber'] ?? null,
            'toBatchNumber' => $data['toBatchNumber'] ?? null,
            'fromLocation' => $data['fromLocation'] ?? null,
            'toLocation' => $data['toLocation'] ?? null,
            'quantity' => $data['quantity'] ?? null,
            'movementTypeId' => $data['movementTypeId'] ?? null,
            'movementTypeName' => $data['movementTypeName'] ?? null,
            'movementDate' => formatDate($data['movementDate']) ?? null,
            'customer' => $data['customer'] ?? null,
            'supplier' => $data['supplier'] ?? null
        ];

        // Construyo una consulta dinámica
        $query = "SELECT * FROM movements m 
        JOIN products p ON m.productCode = p.productCode 
        JOIN movementTypes mt ON m.movementTypeId = mt.movementTypeId 
        WHERE 1=1"; // 1=1 es un truco que simplifica la concatenación de condiciones

        $params = [];

        // Añado condiciones dinámicas según los criterios
        foreach ($criteria as $key => $value) {
            if (!empty($value)) {
                $query .= " AND m.$key = :$key";
                $params[$key] = $value;
            }
        }
        // Preparo y ejecuto la consulta
        $statement = $connection->prepare($query);
        $statement->execute($params);

        // Obtengo los resultados
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        $movementDTO = [];
        foreach ($results as $movement) {
            $movementDTO[] = new movementDTO(
                $movement['productCode'],
                $movement['productName'],
                $movement['fromBatchNumber'],
                $movement['toBatchNumber'],
                $movement['fromLocation'],
                $movement['toLocation'],
                $movement['quantity'],
                $movement['movementTypeId'],
                $movement['movementTypeName'],
                $movement['movementDate'],
                $movement['customer'],
                $movement['supplier'],
            );
        }
        if ($movementDTO == null) {
            return null;
        } else {
            return $movementDTO;
        }
    }

    // POST
    function sale($data)
    {
        $movementService = new MovementService();

        $movement = $movementService->createMovementObject($data);

        // movementTypeId defecto "SA"
        $movement->setMovementTypeId("SA");

        // Valido los datos antes de la inserción, validacionesDeMovement() coge los errores más generales
        $errores = $movement->validacionesDeMovement();

        $connection = $this->db->getConnection();

        $stock = Self::inventoryStock($connection, $data, "ubicacionOrigen");

        // Evaluo si el código de producto no existe
        if (!productCodeVerify($connection, $data)) {
            $errores["productCode"] = 'El código de producto no está registrado en el sistema';
        } elseif ($stock < $data['quantity']) { // Evaluo stock en la tabla inventory
            $errores["quantity"] = 'No hay stock disponible para realizar este movimiento';
        } elseif (empty($data['customer'])) {
            $errores["customer"] = 'El cliente es obligatorio';
        }

        if (empty($errores)) {
            Self::insertMovementDataLine($connection, $movement);

            // Una vez creada la linea en la tabla movements, resto del stock la cantidad solicitada de la tabla inventory
            Self::updateStockLine($connection, $data, "subtraction");

            // Si el stock es cero, elimino la línea de la tabla inventory
            $stockOrigen = Self::inventoryStock($connection, $data, "ubicacionOrigen");
            if ($stockOrigen === 0) {
                Self::deleteStockLineZero($connection, $data);
            }
            return $data;
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

    function purchase($data)
    {
        $movementService = new MovementService();

        $movement = $movementService->createMovementObject($data);
        // movementTypeId defecto "PU"
        $movement->setMovementTypeId("PU");

        // Valido los datos antes de la inserción, validacionesDeMovement() coge los errores más generales
        $errores = $movement->validacionesDeMovement();

        $connection = $this->db->getConnection();

        // Evaluo si el código de producto no existe
        if (!productCodeVerify($connection, $data)) {
            $errores["productCode"] = 'El código de producto no está registrado en el sistema';
        }
        if (empty($data['supplier'])) {
            $errores["supplier"] = 'El proveedor es obligatorio';
        }
        if (empty($data['toBatchNumber'])) {
            $errores["toBatchNumber"] = 'El lote de destino es obligatorio';
        }
        if (empty($data['toLocation'])) {
            $errores["toLocation"] = 'La ubicación de destino es obligatoria';
        }

        if (empty($errores)) {
            Self::insertMovementDataLine($connection, $movement);

            // Una vez creada la linea en la tabla movements, evaluo el stock en la tabla inventory
            // Si no hay stock en la tabla inventory de productCode, batchNumber y location, insertará una nueva línea
            $stockDestino = Self::inventoryStock($connection, $data, "ubicacionDestino");

            if ($stockDestino === null) {
                Self::insertStockLine($connection, $data);
            } else { // Si ya hay stock en la tabla inventory de productCode, batchNumber y location, actualizará la línea
                Self::updateStockLine($connection, $data, "addition");
            }
            return $data;
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

    function inventoryTransfer($data)
    {
        $movementService = new MovementService();

        $movement = $movementService->createMovementObject($data);

        // Valido los datos antes de la inserción, validacionesDeMovement() coge los errores más generales
        $errores = $movement->validacionesDeMovement();

        $connection = $this->db->getConnection();

        // Evaluo si el código de producto no existe
        if (!productCodeVerify($connection, $data)) {
            $errores["productCode"] = 'El código de producto no está registrado en el sistema';
        }
        if (empty($data['fromBatchNumber'])) {
            $errores["fromBatchNumber"] = 'El lote de origen es obligatorio';
        }
        if (empty($data['toBatchNumber'])) {
            $errores["toBatchNumber"] = 'El lote de destino es obligatorio';
        }
        if (empty($data['fromLocation'])) {
            $errores["fromLocation"] = 'La ubicación de origen es obligatoria';
        }
        if (empty($data['toLocation'])) {
            $errores["toLocation"] = 'La ubicación de destino es obligatoria';
        }

        // Evaluo stock en ubicación de origen en la tabla inventory de productCode, batchNumber y location, si hay stock, descontará si no hay, dará mensaje de error
        $stockOrigen = Self::inventoryStock($connection, $data, "ubicacionOrigen");

        if ($stockOrigen < $data['quantity']) {
            $errores["quantity"] = 'No hay stock disponible para realizar este movimiento';
        }

        if (empty($errores)) {
            Self::insertMovementDataLine($connection, $movement);

            // Una vez creada la linea en la tabla movements, evaluo los stocks en la tabla inventory
            // Evaluo stock en ubicación de destino en la tabla inventory de productCode, batchNumber y location, insertará una nueva línea
            $stockDestino = Self::inventoryStock($connection, $data, "ubicacionDestino");

            if ($stockDestino === null) {
                Self::insertStockLine($connection, $data);
            } else {  // Si ya hay stock en la tabla inventory de productCode, batchNumber y location, actualizará la línea
                Self::updateStockLine($connection, $data, "addition");
            }

            // ****** STOCK DE ORIGEN ****** //
            // Si ya hay stock en la tabla inventory de productCode, batchNumber y location, actualizará la línea
            Self::updateStockLine($connection, $data, "subtraction");

            // Si el stock es cero, elimino la línea de la tabla inventory
            $stockOrigen = Self::inventoryStock($connection, $data, "ubicacionOrigen");
            if ($stockOrigen === 0) {
                Self::deleteStockLineZero($connection, $data);
            }

            return $data;
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

    /************** FUNCIONES NECESARIAS ***************/
    function inventoryStock($connection, $data, $locationCriteria)
    {
        // Evaluo el stock en la tabla inventory

        if ($locationCriteria === "ubicacionOrigen") {
            $query = "SELECT stock FROM inventory WHERE productCode = :productCode AND batchNumber = :fromBatchNumber AND location = :fromLocation";
            $statement = $connection->prepare($query);
            // Vinculo los parámetros
            $statement->bindParam(':productCode', $data['productCode'], PDO::PARAM_STR);
            $statement->bindParam(':fromBatchNumber', $data['fromBatchNumber'], PDO::PARAM_STR);
            $statement->bindParam(':fromLocation', $data['fromLocation'], PDO::PARAM_STR);
            // Ejecuto la consulta
            $statement->execute();
        } elseif ($locationCriteria === "ubicacionDestino") {
            $query = "SELECT stock FROM inventory WHERE productCode = :productCode AND batchNumber = :toBatchNumber AND location = :toLocation";
            $statement = $connection->prepare($query);
            // Vinculo los parámetros
            $statement->bindParam(':productCode', $data['productCode'], PDO::PARAM_STR);
            $statement->bindParam(':toBatchNumber', $data['toBatchNumber'], PDO::PARAM_STR);
            $statement->bindParam(':toLocation', $data['toLocation'], PDO::PARAM_STR);
            // Ejecuto la consulta
            $statement->execute();
        }

        // Obtengo el resultado
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        // Al ser un array lo que devuelve $result, debo coger el valor del stock
        $stock = isset($result['stock']) ? (int)$result['stock'] : null;
        return $stock;
    }

    function deleteStockLineZero($connection, $data)
    {
        // Borro la linea de la tabla inventory si el stock es cero
        $deleteQuery = "DELETE FROM inventory WHERE productCode = :productCode AND batchNumber = :fromBatchNumber AND location = :fromLocation";
        $deleteStatement = $connection->prepare($deleteQuery);

        // Vinculo los parámetros
        $deleteStatement->bindParam(':productCode', $data['productCode'], PDO::PARAM_STR);
        $deleteStatement->bindParam(':fromBatchNumber', $data['fromBatchNumber'], PDO::PARAM_STR);
        $deleteStatement->bindParam(':fromLocation',  $data['fromLocation'], PDO::PARAM_STR);

        // Ejecuto la consulta de eliminación
        $deleteStatement->execute();
    }

    function insertStockLine($connection, $data)
    {
        // Inserto una linea en la tabla inventory en caso de no existir stock
        $insertQuery = "INSERT INTO inventory (productCode, batchNumber, location, stock) VALUES (:productCode, :toBatchNumber, :toLocation, :quantity)";
        $insertStatement = $connection->prepare($insertQuery);

        // Vinculo los parámetros
        $insertStatement->bindParam(':productCode', $data['productCode'], PDO::PARAM_STR);
        $insertStatement->bindParam(':toBatchNumber', $data['toBatchNumber'], PDO::PARAM_STR);
        $insertStatement->bindParam(':toLocation',  $data['toLocation'], PDO::PARAM_STR);
        $insertStatement->bindParam(':quantity',  $data['quantity'], PDO::PARAM_INT);

        // Ejecuto la consulta de inserción
        $insertStatement->execute();
    }

    function updateStockLine($connection, $data, $operation)
    {
        // Actualizo la linea en la tabla inventory en caso de existir stock según las condiciones. Sumará o restará de acuerdo con la operación que se le indique
        if ($operation === "addition") {
            $query = "UPDATE inventory SET stock=stock+:quantity WHERE productCode = :productCode AND batchNumber = :toBatchNumber
        AND location = :toLocation";
            $statement = $connection->prepare($query);

            // Vinculo los parámetros
            $statement->bindParam(':productCode', $data['productCode'], PDO::PARAM_STR);
            $statement->bindParam(':toBatchNumber', $data['toBatchNumber'], PDO::PARAM_STR);
            $statement->bindParam(':toLocation',  $data['toLocation'], PDO::PARAM_STR);
            $statement->bindParam(':quantity',  $data['quantity'], PDO::PARAM_INT);
        } elseif ($operation === "subtraction") {
            $query = "UPDATE inventory SET stock=stock-:quantity WHERE productCode = :productCode AND batchNumber = :fromBatchNumber
            AND location = :fromLocation";
            $statement = $connection->prepare($query);

            // Vinculo los parámetros
            $statement->bindParam(':productCode', $data['productCode'], PDO::PARAM_STR);
            $statement->bindParam(':fromBatchNumber', $data['fromBatchNumber'], PDO::PARAM_STR);
            $statement->bindParam(':fromLocation',  $data['fromLocation'], PDO::PARAM_STR);
            $statement->bindParam(':quantity',  $data['quantity'], PDO::PARAM_INT);
        }
        // Ejecuto la consulta
        $statement->execute();
    }

    function insertMovementDataLine($connection, $movement)
    {
        // Inserto linea en la tabla movements
        $datoToInsert = [
            'productCode' => $movement->getProductCode() ?? null,
            'fromBatchNumber' => $movement->getFromBatchNumber() ?? null,
            'toBatchNumber' => $movement->getToBatchNumber() ?? null,
            'fromLocation' => $movement->getFromLocation() ?? null,
            'toLocation' => $movement->getToLocation() ?? null,
            'quantity' => $movement->getQuantity() ?? null,
            'movementTypeId' => $movement->getmovementTypeId() ?? null,
            'movementDate' => $movement->getMovementDate() ?? null,
            'customer' => $movement->getCustomer() ?? null,
            'supplier' => $movement->getSupplier() ?? null
        ];

        // Construyo una consulta SQL dinámica
        $fields = [];
        $placeholders = [];
        $params = [];

        foreach ($datoToInsert as $key => $value) {
            $fields[] = $key;
            $placeholders[] = ":$key";
            $params[":$key"] = $value;
        }

        $fields = implode(', ', $fields);
        $placeholders = implode(', ', $placeholders);

        $query = "INSERT INTO movements ($fields) VALUES ($placeholders)";

        // Preparo y ejecuto la consulta
        $statement = $connection->prepare($query);
        $statement->execute($params);
    }
}

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

        // Valido los datos antes de la inserción, validacionesDeMovement() coge los errores más generales
        $errores = $movement->validacionesDeMovement();

        $connection = $this->db->getConnection();

        // Evaluo si la cantidad a mover es suficiente en la tabla inventory
        $stock = Self::inventoryStock($connection, $data);

        // Evaluo si el código de producto no existe
        if (!productCodeVerify($connection, $data)) {
            $errores["productCode"] = 'El código de producto no está registrado en el sistema';
        } elseif ($stock < $data['quantity']) {
            $errores["quantity"] = 'No hay stock disponible para realizar este movimiento';
        } elseif (empty($data['customer'])) { 
            $errores["customer"] = 'El cliente es obligatorio';
        }

        if (empty($errores)) {
            // Defino los datos a insertar en un array
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

            // Una vez creada la linea en la tabla movements, resto del stock la cantidad solicitada de la tabla inventory
            $query = "UPDATE inventory SET stock=stock-:quantity WHERE productCode = :productCode AND batchNumber = :fromBatchNumber
            AND location = :fromLocation";
            $statement = $connection->prepare($query);

            // Vinculo los parámetros
            $statement->bindParam(':productCode', $data['productCode'], PDO::PARAM_STR);
            $statement->bindParam(':fromBatchNumber', $data['fromBatchNumber'], PDO::PARAM_STR);
            $statement->bindParam(':fromLocation', $data['fromLocation'], PDO::PARAM_STR);
            $statement->bindParam(':quantity', $data['quantity'], PDO::PARAM_STR);

            // Ejecuto la consulta
            $statement->execute();



            // Si el stock es cero, elimino la línea de la tabla inventory
            $stock = Self::inventoryStock($connection, $data);

            if ($stock === 0) {
                $deleteQuery = "DELETE FROM inventory WHERE productCode = :productCode AND batchNumber = :fromBatchNumber AND location = :fromLocation";
                $deleteStatement = $connection->prepare($deleteQuery);

                // Vinculo los parámetros
                $deleteStatement->bindParam(':productCode', $data['productCode'], PDO::PARAM_STR);
                $deleteStatement->bindParam(':fromBatchNumber', $data['fromBatchNumber'], PDO::PARAM_STR);
                $deleteStatement->bindParam(':fromLocation',  $data['fromLocation'], PDO::PARAM_STR);

                // Ejecuto la consulta de eliminación
                $deleteStatement->execute();
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

    function purchase($data) {}


    function inventoryTransfer($data) {}

    function inventoryStock($connection, $data)
    {
        // Evaluo el stock en la tabla inventory
        $query = "SELECT stock FROM inventory WHERE productCode = :productCode AND batchNumber = :fromBatchNumber
                AND location = :fromLocation";
        $statement = $connection->prepare($query);

        // Vinculo los parámetros
        $statement->bindParam(':productCode', $data['productCode'], PDO::PARAM_STR);
        $statement->bindParam(':fromBatchNumber', $data['fromBatchNumber'], PDO::PARAM_STR);
        $statement->bindParam(':fromLocation', $data['fromLocation'], PDO::PARAM_STR);

        // Ejecuto la consulta
        $statement->execute();
        // Obtengo el resultado
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        // Al ser un array lo que devuelve $result, debo coger el valor del stock
        $stock = isset($result['stock']) ? (int)$result['stock'] : null;
        return $stock;
    }
}

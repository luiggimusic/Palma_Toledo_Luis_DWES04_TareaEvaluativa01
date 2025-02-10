<?php

// Validación del DNI del usuario
function letraNif($numero)
{
    return substr("TRWAGMYFPDXBNJZSQVHLCKE", strtr($numero, "XYZ", "012") % 23, 1);
}

function validarDNI($dni)
{
    $numero = substr($dni, 0, 8);
    $letra = letraNif($numero);
    return $dni == $numero . $letra;
}

// Convierto la fecha de DD-MM-YYYY a YYYY-MM-DD
function formatDate(string $date): ?string
{
    $dateTime = DateTime::createFromFormat('d/m/Y', $date);
    return $dateTime ? $dateTime->format('Y-m-d') : null; // para recordarlo: expresión condicional ternaria
}

// Este método controla la respuestas
function sendJsonResponse($apiResponse)
{
    header('Content-Type: application/json');
    http_response_code($apiResponse->getCode());
    echo $apiResponse->toJSON();
}

// Verifica si el código de producto existe en la BBDD
function productCodeVerify($connection, $data)
{
    $query = "SELECT COUNT(*) FROM products WHERE productCode = :productCode";
    $statement = $connection->prepare($query);

    if ($statement) {
        $statement->execute(['productCode' => $data['productCode']]);
        $count = $statement->fetchColumn();
        return $count == 1;
    } else {
        // Controlo el error si la preparación de la consulta falla
        throw new Exception("Error al preparar la consulta SQL.");
    }
}

// Verifico si el ID del tipo de movimiento existe en la BBDD
function movementTypeIdVerify($connection, $data)
{
    $query = "SELECT COUNT(*) FROM movementTypes WHERE movementTypeId = :movementTypeId";
    $statement = $connection->prepare($query);
    $statement->execute(['movementTypeId' => $data['movementTypeId']]);
    $count = $statement->fetchColumn();
    if ($count == 1) {
        return true;
    }
}

// Verifico si el departmentId está registrado en la tabla departments
function departmentIdVerify($connection, $data)
{
    // Verifico si el departmentId está registrado en la tabla departments
    $query = "SELECT COUNT(*) FROM departments WHERE departmentId = :departmentId";
    $statement = $connection->prepare($query);
    if ($statement) {
        $statement->execute(['departmentId' => $data['departmentId']]);
        $count = $statement->fetchColumn();
        return $count == 1;
    } else {
        // Controlo el error si la preparación de la consulta falla
        throw new Exception("Error al preparar la consulta SQL.");
    }
}

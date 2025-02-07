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

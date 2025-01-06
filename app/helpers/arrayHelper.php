<?php
    // Con esta función pretendo pasar el array de objetos y que devuelva el objeto si lo encuentra o null si no.
    function getElementById($dataArray,$id)
    {
        foreach ($dataArray as $data) {
            if ($data['id'] === $id) {
                $data = json_encode($data);
                return $data;
                break;
            }
        }
        return null;
    }

function getId($dataArray, $id)
{
    foreach ($dataArray as $data) {
        if ($data['id'] === $id) {
            return true;
            break;
        }
    }
    return false;
}

function nextId($dataArray){
    $ids = array_column($dataArray, 'id'); // Extraigo los IDs de su columna
    return empty($ids) ? 1 : max($ids) + 1; // Aquí me he animado a poner la estructura condicional con operador ternario

}

// Verifico si el ID ya existe
function existsObjectId($dataArray, $value, $key)
{
    foreach ($dataArray as $data) {
        if (isset($data[$key]) && $data[$key] === $value) {
            return true;
        }
    }
    return false;
}

// Función para garantizar que no se duplique el ID. En el caso de User será el DNI
function existeIdExcluyendo($dataArray, $idObjeto, $id, $key)
{
    foreach ($dataArray as $data) {
        if (isset($data[$key]) && $data[$key] === $idObjeto && $data['id'] !== $id) {
            return true; // ID encontrado en otro en el array
        }
    }
    return false; // ID no encontrado o pertenece al objeto actual
}

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

// Verifico si el DNI ya existe
function existeDNI($usersArray, $dni)
{
    foreach ($usersArray as $user) {
        if ($user['dni'] === $dni) {
            return true;
        }
    }
    return false;
}
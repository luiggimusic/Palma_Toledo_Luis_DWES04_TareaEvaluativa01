<?php

declare(strict_types=1);

require_once '../app/models/User.php'; // cargo el modelo User
require_once '../app/helpers/arrayHelper.php'; // cargo el fichero con las funciones que me permitirán trabajar con los arrays

class UserController
{
    // GET
    function getAllUsers()
    {
        $usersArray = User::getAll(); // Llamo a la función getAll para obtener todos los usuarios
        print_r($usersArray);
    }

    function getUserById($id)
    {
        $success = User::getById($id);
        if ($success) {
            echo "Status Code: 200 OK\nUsuario encontrado\n";
            print_r($success);
        } else {
            echo "Status Code: 409 Conflict\nNo se ha encontrado el usuario";
        }
    }

    // POST
    function createUser($data)
    {
        $userData = [
            'name' => $data["name"],
            'surname' => $data["surname"],
            'dni' => $data["dni"],
            'dateOfBirth' => $data["dateOfBirth"],
            'department' => $data["department"]
        ];

        // Llamo al método estático "create" para agregar al usuario
        $success = User::create($userData);

        if ($success) {
            echo "Status Code: 201 OK\nUsuario creado correctamente";
        } else {
            echo "Status Code: 409 Conflict\nNo se ha creado el usuario";
        }
    }

    // PUT
    function updateUser($id, $data)
    { // Creo un array asociativo con los datos recibidos
        $userData = [
            'id' => $id,
            'name' => $data["name"],
            'surname' => $data["surname"],
            'dni' => $data["dni"],
            'dateOfBirth' => $data["dateOfBirth"],
            'department' => $data["department"]
        ];

        // Llamo al método estático "update" para actualizar al usuario
        $success = User::update($id, $userData);

        if ($success) {
            echo "Status Code: 204 OK\nUsuario actualizado correctamente";
        } else {
            echo "Status Code: 409 Conflict\nError al actualizar";
        }
    }

    // DELETE
    function deleteUser($id)
    {
        $success = User::delete($id);

        if ($success) {
            echo "Status Code: 204 OK\nUsuario eliminado";
        } else {
            echo "Status Code: 409 Conflict\nError al eliminar";
        }
    }
}

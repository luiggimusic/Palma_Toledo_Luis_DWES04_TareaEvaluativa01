<?php

declare(strict_types=1);

header('Content-Type: application/json'); // le indico al cliente que la respuesta es de tipo JSON.
require_once '../app/models/DAO/UserDAO.php';
require_once '../app/utils/ApiResponse.php';
require_once '../app/helpers/helper.php';

class UserController
{
    private $userDAO;

    function __construct()
    {
        $this->userDAO = new UserDAO();
    }

    // GET
    public function getAllUsers()
    {
        $users = $this->userDAO->getAllUsers();

        if (isset($users)) {
            return sendJsonResponse(new ApiResponse(
                status: 'success',
                code: 200,
                message: 'Datos cargados correctamente',
                data: $users
            ));
        } else {
            return sendJsonResponse(new ApiResponse(
                status: 'not success',
                code: 500,
                message: 'Error al leer los datos',
                data: $users
            ));
        }
        $users = json_encode($users);
        print_r($users);
    }

    function getUserById($id)
    {
        $users = $this->userDAO->getUserById($id);

        if (isset($users)) {
            return sendJsonResponse(new ApiResponse(
                status: 'success',
                code: 200,
                message: 'Datos cargados correctamente',
                data: $users
            ));
        } else {
            return sendJsonResponse(new ApiResponse(
                status: 'not success',
                code: 500,
                message: 'Usuario no encontrado',
                data: $users
            ));
        }
        $users = json_encode($users);
        print_r($users);
    }

    // POST
    function createUser($data)
    {
        $user = $this->userDAO->createUser($data);

        if (isset($user)) {
            return sendJsonResponse(new ApiResponse(
                status: 'success',
                code: 200,
                message: 'Datos cargados correctamente',
                data: $user
            ));
        }
    }

    // PUT
    function updateUser($data)
    {
        $user = $this->userDAO->updateUser($data);

        if (isset($user)) {
            return sendJsonResponse(new ApiResponse(
                status: 'success',
                code: 200,
                message: 'Datos cargados correctamente',
                data: $user
            ));
        }
    }

    // DELETE
    function deleteUser($data)
    {
        $user = $this->userDAO->deleteUser($data);

        if (isset($user)) {
            return sendJsonResponse(new ApiResponse(
                status: 'success',
                code: 200,
                message: 'Usuario eliminado correctamente',
                data: $user
            ));
        }
    }
}

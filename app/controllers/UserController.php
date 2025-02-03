<?php

declare(strict_types=1);
// header('Content-Type: application/json'); // le indico al cliente que la respuesta es de tipo JSON.

require '../app/models/DAO/UserDAO.php';
require '../app/utils/ApiResponse.php';
require '../app/helpers/arrayHelper.php';




// require_once '../app/models/User.php'; // cargo el modelo User
// require_once '../app/helpers/arrayHelper.php'; // cargo el fichero con las funciones que me permitirán trabajar con los arrays

class UserController
{
    private $userDAO;

    public function __construct()
    {
        $this->userDAO = new UserDAO();
    }


    // GET
    public function getAllUsers()
    {
        // $userDAO = new UserDAO();
        $users = $this->userDAO->getAllUsers();

        if (isset($users)) {
            return $this->sendJsonResponse(new ApiResponse(
                status: 'success',
                code: 200,
                message: 'Datos cargados correctamente',
                data: $users
            ));
        } else {
            return $this->sendJsonResponse(new ApiResponse(
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
            return $this->sendJsonResponse(new ApiResponse(
                status: 'success',
                code: 200,
                message: 'Datos cargados correctamente',
                data: $users
            ));
        } else {
            return $this->sendJsonResponse(new ApiResponse(
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
        // Creo instancia del modelo User
        $userNew = new User(
            $data["name"] ?? "",
            $data["surname"] ?? "",
            $data["dni"] ?? "",
            $data["dateOfBirth"] ?? "",
            $data["departmentId"] ?? ""
        );
        // Valido datos antes de la inserción
        $errores = $userNew->validacionesDeUsuario();

        var_dump($errores);
        if (empty($errores) && isset($user)) {

            $user = $this->userDAO->createUser($data);
            // var_dump($user);
            // if (isset($user)) {
                return $this->sendJsonResponse(new ApiResponse(
                    status: 'success',
                    code: 200,
                    message: 'Datos cargados correctamente',
                    data: $user
                ));
            } else {
                return $this->sendJsonResponse(new ApiResponse(
                    status: 'not success',
                    code: 500,
                    message: 'Error al crear el usuario',
                    data: null
                ));
            }
        
        // // Valido datos antes de la inserción
        // $errores = $userNew->validacionesDeUsuario();
        // if (!empty($errores)) {
        //     http_response_code(400);
        //     echo json_encode(["errors" => $errores]);
        //     return;
        // }

        // $user = $this->userDAO->createUser($data);
        // // var_dump($user);
        // if (isset($user)) {
        //     return $this->sendJsonResponse(new ApiResponse(
        //         status: 'success',
        //         code: 200,
        //         message: 'Datos cargados correctamente',
        //         data: $user
        //     ));
        // } else {
        //     return $this->sendJsonResponse(new ApiResponse(
        //         status: 'not success',
        //         code: 500,
        //         message: 'Error al crear el usuario',
        //         data: $user
        //     ));
        // }


        $user = json_encode($userNew);
        print_r($user);
    }

    // PUT
    function updateUser($id, $data)
    { // Creo un array asociativo con los datos recibidos
        // $userData = [
        //     'id' => $id,
        //     'name' => $data["name"],
        //     'surname' => $data["surname"],
        //     'dni' => $data["dni"],
        //     'dateOfBirth' => $data["dateOfBirth"],
        //     'department' => $data["department"]
        // ];

        // // Llamo al método estático "update" para actualizar al usuario
        // $success = User::update($id, $userData);

        // if ($success) {
        //     echo "Status Code: 204 OK\nUsuario actualizado correctamente";
        // } else {
        //     echo "Status Code: 409 Conflict\nError al actualizar";
        // }
    }

    // DELETE
    static function deleteUser($id)
    {
        // $success = User::delete($id);

        // if ($success) {
        //     echo "Status Code: 204 OK\nUsuario eliminado";
        // } else {
        //     echo "Status Code: 409 Conflict\nError al eliminar";
        // }

        $userDAO = new UserDAO();
        $users = $userDAO->deleteUser($id);
        // echo json_encode($users);
    }

    private function sendJsonResponse($apiResponse)
    {
        header('Content-Type: application/json');
        http_response_code($apiResponse->getCode());
        echo $apiResponse->toJSON();
    }
}

<?php

require '../app/core/DatabaseSingleton.php';
require '../app/models/DTO/UserDTO.php';
require '../app/models/entity/UserEntity.php';
require '../app/services/UserService.php';

class UserDAO
{
    private $db;
    public function __construct()
    {
        $this->db = DatabaseSingleton::getInstance();
    }

    // CRUD
    // GET
    function getAllUsers()
    {
        $connection = $this->db->getConnection();
        $query = "SELECT * FROM users u JOIN departments d ON u.departmentId = d.departmentId";
        $statement = $connection->query($query);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        $usersDTO = [];
        foreach ($result as $user) {
            $usersDTO[] = new UserDTO(
                $user['dni'],
                $user['name'],
                $user['surname'],
                $user['departmentName'],
            );
        }
        if ($usersDTO == null) {
            return null;
        } else {
            return $usersDTO;
        }
    }

    function getUserById($id)
    {
        $connection = $this->db->getConnection();
        $query = "SELECT * FROM users u JOIN departments d ON u.departmentId = d.departmentId WHERE u.id = $id";
        $statement = $connection->query($query);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        $usersDTO = [];

        foreach ($result as $user) {
            $usersDTO[] = new UserDTO(
                $user['dni'],
                $user['name'],
                $user['surname'],
                $user['departmentName'],
            );
        }
        if ($usersDTO == null) {
            return null;
        } else {
            return $usersDTO;
        }
    }

    // POST
    function createUser($data)
    {
        $connection = $this->db->getConnection();

        // Con userService creo un objeto para evitar código en los otros métodos
        $userService = new UserService($connection);
        $user = $userService->createUserObject($data);

        // Valido los datos antes de la inserción
        $errores = $user->validacionesDeUsuario($userService,false);

        // Verifico si el departmentId está registrado en la tabla departments
        if (!departmentIdVerify($connection, $data)) {
            $errores["departmentId"] = 'El departamento ID: ' . strtoupper($data['departmentId']) .
                ' no existe en el sistema';
        }

        if (empty($errores)) {
            $query = "INSERT INTO users (name, surname, dni, dateOfBirth, departmentId) 
                  VALUES (:name, :surname, :dni, :dateOfBirth, :departmentId)";
            $statement = $connection->prepare($query);
            $statement->execute([
                'name' => $user->getName(),
                'surname' => $user->getSurname(),
                'dni' => $user->getDni(),
                'dateOfBirth' => $user->getDateOfBirth(),  // Debe estar en formato YYYY-MM-DD
                'departmentId' => $user->getDepartmentId(),
            ]);

            // Obtengo los datos del usuario para mostrarlo en la respuesta
            $user = Self::showUserData($connection, $data);
            return $user;
        } else {
            sendJsonResponse(new ApiResponse(
                status: 'not success',
                code: 400,
                message: $errores,
                data: null
            ));
            return null;
        }
    }

    // PUT
    function updateUser($data)
    {
        $connection = $this->db->getConnection();
        $userService = new UserService($connection);
        $user = $userService->createUserObject($data);

        // Valido datos antes de la inserción
        $errores = $user->validacionesDeUsuario($userService,true);

        // Si el departmentId no existe, añadirá el mensaje de error
        if (!departmentIdVerify($connection, $data)) {
            $errores["departmentId"] = 'El departamento ID: ' . strtoupper($data['departmentId']) .
                ' no existe en el sistema';
        }
        if (empty($errores)) {
            $query = "UPDATE users SET name=:name, surname=:surname, dateOfBirth=:dateOfBirth, departmentId=:departmentId WHERE dni=:dni";
            $statement = $connection->prepare($query);
            $userUpdate = $statement->execute([
                'name' => $user->getName(),
                'surname' => $user->getSurname(),
                'dni' => $user->getDni(),  // Asegúrate de descomentar esta línea si es necesario
                'dateOfBirth' => $user->getDateOfBirth(),
                'departmentId' => $user->getDepartmentId(),
            ]);

            // Obtengo los datos del usuario para mostrarlo en la respuesta
            $user = Self::showUserData($connection, $data);
            return $user;
        } else {
            sendJsonResponse(new ApiResponse(
                status: 'not success',
                code: 400,
                message: $errores,
                data: null
            ));
            return null;
        }
    }

    // DELETE
    function deleteUser($data)
    {
        $connection = $this->db->getConnection();
        $userService = new UserService($connection);
        $user = $userService->createUserObject($data);

        // Obtengo los datos del usuario para mostrarlo en la respuesta, 
        // en este caso antes de eliminar la tupla
        $userDeleteData = Self::showUserData($connection, $data);

        // Si el DNI no existe, añadirá el mensaje de error
        $errores = [];
        if (!$userService->dniVerify( $user)) {
            $errores["dni"] = 'El DNI no está registrado en el sistema';
        }
        if (empty($errores)) {
            $query = "DELETE FROM users WHERE dni = :dni";
            $statement = $connection->prepare($query);
            $statement->bindParam(':dni', $data['dni'], PDO::PARAM_STR);
            $statement->execute();

            // Una vez ejecutada la eliminación, envío los datos del elemento eliminado para que se vean en la respuesta
            return $userDeleteData;
        } else {
            sendJsonResponse(new ApiResponse(
                status: 'not success',
                code: 400,
                message: $errores,
                data: null
            ));
            return null;
        }
    }

    private function showUserData($connection, $data)
    {
        // Obtengo los datos del usuario actualizado para mostrarlo en la respuesta
        $query = "SELECT * FROM users WHERE dni = :dni";
        $statement = $connection->prepare($query);
        $statement->execute(['dni' => $data['dni']]);
        $userData = $statement->fetch(PDO::FETCH_ASSOC);
        return $userData;
    }
}

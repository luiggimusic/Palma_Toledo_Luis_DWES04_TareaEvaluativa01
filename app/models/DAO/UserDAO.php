<?php
require '../app/core/DatabaseSingleton.php';
require '../app/models/DTO/UserDTO.php';
require '../app/models/User.php';
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
        // Con userService creo un objeto para evitar código en los otros métodos
        $userService = new UserService();

        $user = $userService->createUserObject($data);

        // Valido los datos antes de la inserción
        $errores = $user->validacionesDeUsuario();

        $connection = $this->db->getConnection();

        // Verifico si el DNI ya existe
        $query = "SELECT COUNT(*) FROM users WHERE dni = :dni";
        $statement = $connection->prepare($query);
        $statement->execute(['dni' => $data['dni']]);
        $count = $statement->fetchColumn();

        // Si el DNI ya existe, añadirá el mensaje de error
        if ($count > 0) {
            $errores["dni"] = 'El DNI ya está registrado en el sistema';
        }

        // Verifico si el departmentId está registrado en la tabla departments
        if (!Self::departmentVerify($connection, $data)) {
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
            $this->sendJsonResponse(new ApiResponse(
                status: 'error',
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





        $userService = new UserService();
        $user = $userService->createUserObject($data);
        $connection = $this->db->getConnection();

        // Valido datos antes de la inserción
        $errores = $user->validacionesDeUsuario();

        if (!Self::departmentVerify($connection, $data)) {
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
            $this->sendJsonResponse(new ApiResponse(
                status: 'error',
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
        $query = "DELETE * FROM users WHERE dni = :dni";
        $statement = $connection->prepare($query);
        $statement->execute(['dni' => $data['dni']]);


        $result = $statement->fetchAll(PDO::FETCH_ASSOC);


    }

    private function departmentVerify($connection, $data)
    {
        // Verifico si el departmentId está registrado en la tabla departments
        $query = "SELECT COUNT(*) FROM departments WHERE departmentId = :departmentId";
        $statement = $connection->prepare($query);
        $statement->execute(['departmentId' => $data['departmentId']]);
        $count = $statement->fetchColumn();

        // Si encuentra departmentId, devuelve true
        if ($count == 1) {
            return true;
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



    private function sendJsonResponse($apiResponse)
    {
        header('Content-Type: application/json');
        http_response_code($apiResponse->getCode());
        echo $apiResponse->toJSON();
    }
}

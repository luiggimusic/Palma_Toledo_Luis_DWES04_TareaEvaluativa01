<?php
require '../app/core/DatabaseSingleton.php';
require '../app/models/DTO/UserDTO.php';
require '../app/models/User.php';

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

        // Verifico si el DNI ya existe
        $query = "SELECT COUNT(*) FROM users WHERE dni = :dni";
        $statement = $connection->prepare($query);
        $statement->execute(['dni' => $data['dni']]);
        $count = $statement->fetchColumn();

        // Si el DNI ya existe, añadirá el mensaje de error
        if ($count > 0) {
            $errores["dni"] = 'El DNI ya está registrado en el sistema';
        }

        if (empty($errores)) {
            $query = "INSERT INTO users (name, surname, dni, dateOfBirth, departmentId) 
                  VALUES (:name, :surname, :dni, :dateOfBirth, :departmentId)";
            $statement = $connection->prepare($query);
            $statement->execute([
                'name' => $userNew->getName(),
                'surname' => $userNew->getSurname(),
                'dni' => $userNew->getDni(),
                'dateOfBirth' => $userNew->getDateOfBirth(),  // Debe estar en formato YYYY-MM-DD
                'departmentId' => $userNew->getDepartmentId(),
            ]);

            // Obtengo el último ID insertado para luego poder mostrarlo en la respuesta
            $lastId = $connection->lastInsertId();

            // Recupero el usuario recién insertado
            $query = "SELECT * FROM users WHERE id = :id";
            $statement = $connection->prepare($query);
            $statement->execute(['id' => $lastId]);
            $user = $statement->fetch(PDO::FETCH_ASSOC);
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
        $connection = $this->db->getConnection();

        // Creo instancia del modelo User
        $user = new User(
            $data["name"],
            $data["surname"],
            $data["dni"],
            $data["dateOfBirth"],
            $data["departmentId"]
        );

        // Uso los setters para actualizar los datos
        if (isset($data['name'])) {
            $user->setName($data['name']);
        }
        if (isset($data['surname'])) {
            $user->setSurname($data['surname']);
        }
        if (isset($data['dni'])) {
            $user->setDni($data['dni']);
        }
        if (isset($data['dateOfBirth'])) {
            $user->setDateOfBirth($data['dateOfBirth']);
        }
        if (isset($data['departmentId'])) {
            $user->setDepartmentId($data['departmentId']);
        }




        // Valido datos antes de la inserción
        $errores = $user->validacionesDeUsuario();

        // Verifico si el DNI ya existe
        // $query = "SELECT COUNT(*) FROM users WHERE dni = :dni";
        // $statement = $connection->prepare($query);
        // $statement->execute(['dni' => $data['dni']]);
        // $count = $statement->fetchColumn();

        // Si el DNI ya existe, añadirá el mensaje de error
        // if ($count > 0) {
        //     $errores["dni"] = 'El DNI ya está registrado en el sistema';
        // }

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



            // Obtengo el último ID insertado para luego poder mostrarlo en la respuesta
            // $lastId = $connection->lastInsertId();

            // Recupero el usuario recién insertado
            // $query = "SELECT * FROM users WHERE id = :id";
            // $statement = $connection->prepare($query);
            // $statement->execute(['id' => $lastId]);
            // $user = $statement->fetch(PDO::FETCH_ASSOC);

            return $userUpdate;
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
    function deleteUser($dni)
    {

        // global $bd;
        // $sql = "DELETE FROM equipo WHERE idequipo =".$id;
        // $bd->eliminar($sql);

        $connection = $this->db->getConnection();
        $query = "DELETE * FROM users WHERE dni = '" . $dni . "'";
        $statement = $connection->query($query);
        // $result = $statement->fetchAll(PDO::FETCH_ASSOC);


    }



    private function sendJsonResponse($apiResponse)
    {
        header('Content-Type: application/json');
        http_response_code($apiResponse->getCode());
        echo $apiResponse->toJSON();
    }
}

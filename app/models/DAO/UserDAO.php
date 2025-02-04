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

        if ($count > 0) {
            // El DNI ya está registrado
            $errores["dni"] = 'El DNI ya está registrado en el sistema';
            // return [
            //     'error' => true,
            //     'message' => 'El DNI ya está registrado en el sistema.'
            // ];
        }


  
        if (empty($errores)) {


            $query = "INSERT INTO users (name, surname, dni, dateOfBirth, departmentId) 
                  VALUES (:name, :surname, :dni, :dateOfBirth, :departmentId)";
            $statement = $connection->prepare($query);
            $statement->execute([
                'name' => $data['name'],
                'surname' => $data['surname'],
                'dni' => $data['dni'],
                'dateOfBirth' => $data['dateOfBirth'],
                'departmentId' => $data['departmentId'],
            ]);


            // Obtener el último ID insertado
            $lastId = $connection->lastInsertId();

            // Recuperar el usuario recién insertado
            $query = "SELECT * FROM users WHERE id = :id";
            $statement = $connection->prepare($query);
            $statement->execute(['id' => $lastId]);
            $user = $statement->fetch(PDO::FETCH_ASSOC);



            if ($user == null) {
                print_r($errores);
                return null;
            } else {
                // var_dump($user);
                return $user;
            }
        } // cierre del empty errores 
        else {

            
        $errores = json_encode($errores);
   
            print_r($errores);
            return null;
        }

    }

    // PUT
    function updateUser($id, $data) {}

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
}

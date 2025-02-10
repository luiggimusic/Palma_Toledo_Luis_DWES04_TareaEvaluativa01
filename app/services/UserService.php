<?php
class UserService
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }
    public function createUserObject($data)
    {
        // Creo instancia del modelo User
        $user = new UserEntity("", "", "", '01/01/2023', "");

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
            $user->setDateOfBirth(formatDate($data['dateOfBirth']));
        }
        if (isset($data['departmentId'])) {
            $user->setDepartmentId($data['departmentId']);
        }

        // Devuelvo el objeto User
        return $user;
    }

    public function dniVerify(UserEntity $user)
    {
        $query = "SELECT COUNT(*) FROM users WHERE dni = :dni";
        $statement = $this->connection->prepare($query);
        $statement->execute(['dni' => $user->getDni()]);
        $count = $statement->fetchColumn();
        return $count == 1;
    }
}

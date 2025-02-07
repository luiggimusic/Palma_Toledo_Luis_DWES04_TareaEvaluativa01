<?php
class UserService {
    public function createUserObject($data) {
        // Creo instancia del modelo User
        $user = new User("","","",'01/01/2023',"");

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
}

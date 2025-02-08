<?php
require '../app/models/DTO/DepartmentDTO.php';
require '../app/models/entity/DepartmentEntity.php';
require '../app/services/DepartmentService.php';

class DepartmentDAO
{
    private $db;
    public function __construct()
    {
        $this->db = DatabaseSingleton::getInstance();
    }

    // CRUD
    // GET
    function getAllDepartments()
    {
        $connection = $this->db->getConnection();
        $query = "SELECT * FROM departments";
        $statement = $connection->query($query);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        $departmentsDTO = [];
        foreach ($result as $department) {
            $departmentsDTO[] = new DepartmentDTO(
                $department['departmentId'],
                $department['departmentName'],
            );
        }
        if ($departmentsDTO == null) {
            return null;
        } else {
            return $departmentsDTO;
        }
    }

    function getDepartmentById($id)
    {
        $connection = $this->db->getConnection();
        $query = "SELECT * FROM departments WHERE id = $id";
        $statement = $connection->query($query);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        $departmentsDTO = [];
        foreach ($result as $department) {
            $departmentsDTO[] = new DepartmentDTO(
                $department['departmentId'],
                $department['departmentName'],
            );
        }
        if ($departmentsDTO == null) {
            return null;
        } else {
            return $departmentsDTO;
        }
    }

    // POST
    function createDepartment($data)
    {
        // Creo un objeto que me permitirá dar la respuesta sin duplicar código
        $departmentService = new DepartmentService();
        $department = $departmentService->createDepartmentObject($data);

        // Valido los datos antes de la inserción
        $errores = $department->validacionesDeDepartment();

        $connection = $this->db->getConnection();

        // Si el departmentId ya existe, añadirá el mensaje de error
        if (Self::departmentIdVerify($connection, $data)) {
            $errores["departmentId"] = 'El ID del departamento ya está registrado en el sistema';
        }

        if (empty($errores)) {
            $query = "INSERT INTO departments (departmentId, departmentName) 
                  VALUES (:departmentId, :departmentName)";
            $statement = $connection->prepare($query);
            $statement->execute([
                'departmentId' => $department->getDepartmentId(),
                'departmentName' => $department->getDepartmentName(),
            ]);

            // Obtengo los datos para mostrarlo en la respuesta
            $department = Self::showDepartmentData($connection, $data);
            return $department;
        } else {
            sendJsonResponse(new ApiResponse(
                status: 'error',
                code: 400,
                message: $errores,
                data: null
            ));
            return null;
        }
    }

    // PUT
    function updateDepartment($data)
    {
        $departmentService = new DepartmentService();
        $department = $departmentService->createDepartmentObject($data);

        // Valido los datos antes de la inserción
        $errores = $department->validacionesDeDepartment();
        $connection = $this->db->getConnection();

        // Si el departmentId no existe, añadirá el mensaje de error
        if (!Self::departmentIdVerify($connection, $data)) {
            $errores["departmentId"] = 'El ID del departamento no está registrado en el sistema';
        }

        if (empty($errores)) {
            $query = "UPDATE departments SET departmentId=:departmentId, departmentName=:departmentName WHERE departmentId=:departmentId";
            $statement = $connection->prepare($query);
            $statement->execute([
                'departmentId' => $department->getDepartmentId(),
                'departmentName' => $department->getDepartmentName(),
            ]);

            // Obtengo los datos del departamento para mostrarlos en la respuesta
            $department = Self::showDepartmentData($connection, $data);
            return $department;
        } else {
            sendJsonResponse(new ApiResponse(
                status: 'error',
                code: 400,
                message: $errores,
                data: null
            ));
            return null;
        }
    }

    // DELETE
    function deleteDepartment($data)
    {
        $connection = $this->db->getConnection();

        // Obtengo los datos para mostrarlo en la respuesta, 
        // en este caso antes de eliminar la tupla
        $department = Self::showDepartmentData($connection, $data);

        // Verifico si el departmentId está en uso en la tabla users
        if (!Self::departmentIdInUseVerify($connection, $data)) {
            $errores["departmentId"] = 'El ID del departamento está relacionado con un usuario';
        }

        // Si el departmentId no existe, añadirá el mensaje de error
        if (!Self::departmentIdVerify($connection, $data)) {
            $errores["departmentId"] = 'El ID del departamento no está registrado en el sistema';
        }
        if (empty($errores)) {
            $query = "DELETE FROM departments WHERE departmentId=:departmentId";
            $statement = $connection->prepare($query);
            $statement->bindParam(':departmentId', $data['departmentId'], PDO::PARAM_STR);
            $statement->execute();

            // Una vez ejecutada la eliminación, envío los datos del elemento eliminado para que se vean en la respuesta
            return $department;
        } else {
            sendJsonResponse(new ApiResponse(
                status: 'error',
                code: 400,
                message: $errores,
                data: null
            ));
            return null;
        }
    }
    private function departmentIdVerify($connection, $data)
    {
        // Verifico si el departmentId está registrado en la tabla departments
        $query = "SELECT COUNT(*) FROM departments WHERE departmentId = :departmentId";
        $statement = $connection->prepare($query);
        if ($statement) {
            $statement->execute(['departmentId' => $data['departmentId']]);
            $count = $statement->fetchColumn();
            return $count == 1;
        } else {
            // Controlo el error si la preparación de la consulta falla
            throw new Exception("Error al preparar la consulta SQL.");
        }
    }

    private function departmentIdInUseVerify($connection, $data)
    {
        // Verifico si el departmentId está siendo usado en la tabla users
        $query = "SELECT COUNT(*) FROM users WHERE departmentId = :departmentId";
        $statement = $connection->prepare($query);
        if ($statement) {
            $statement->execute(['departmentId' => $data['departmentId']]);
            $count = $statement->fetchColumn();
            return $count == 1;
        } else {
            // Controlo el error si la preparación de la consulta falla
            throw new Exception("Error al preparar la consulta SQL.");
        }
    }

    private function showDepartmentData($connection, $data)
    {
        // Obtengo los datos del departamento actualizado para mostrarlo en la respuesta
        $query = "SELECT * FROM departments WHERE departmentId = :departmentId";
        $statement = $connection->prepare($query);
        $statement->execute(['departmentId' => $data['departmentId']]);
        $departmentData = $statement->fetch(PDO::FETCH_ASSOC);
        return $departmentData;
    }
}

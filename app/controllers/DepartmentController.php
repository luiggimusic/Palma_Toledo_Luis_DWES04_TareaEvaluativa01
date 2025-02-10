<?php

declare(strict_types=1);
header('Content-Type: application/json'); // le indico al cliente que la respuesta es de tipo JSON.

require_once '../app/models/DAO/DepartmentDAO.php'; 
require_once '../app/helpers/helper.php'; 

class DepartmentController
{
    private $DepartmentDAO;
    private $input; // Capturo el cuerpo de la solicitud

    function __construct() {
        $this->DepartmentDAO = new DepartmentDAO();    
        $this->input = json_decode(file_get_contents('php://input'),true);
    }

    // GET
    function getAllDepartments()
    {
        $departments = $this->DepartmentDAO->getAllDepartments();

        if (isset($departments)) {
            return sendJsonResponse(new ApiResponse(
                status: 'success',
                code: 200,
                message: 'Datos cargados correctamente',
                data: $departments
            ));
        } else {
            return sendJsonResponse(new ApiResponse(
                status: 'not success',
                code: 500,
                message: 'Error al leer los datos',
                data: $departments
            ));
        }
        $departments = json_encode($departments);
        print_r($departments);
    }

    function getDepartmentById($id)
    {
        $department = $this->DepartmentDAO->getDepartmentById($id);

        if (isset($department)) {
            return sendJsonResponse(new ApiResponse(
                status: 'success',
                code: 200,
                message: 'Datos cargados correctamente',
                data: $department
            ));
        } else {
            return sendJsonResponse(new ApiResponse(
                status: 'not success',
                code: 500,
                message: 'ID del departamento no encontrado',
                data: $department
            ));
        }
        $department = json_encode($department);
        print_r($department);
    }

    // POST
    function createDepartment()
    {
        $department = $this->DepartmentDAO->createDepartment($this->input);

        if (isset($department)) {
            return sendJsonResponse(new ApiResponse(
                status: 'success',
                code: 200,
                message: 'Datos cargados correctamente',
                data: $department
            ));
        }
    }

    // PUT
    function updateDepartment()
    {
        $department = $this->DepartmentDAO->updateDepartment($this->input);

        if (isset($department)) {
            return sendJsonResponse(new ApiResponse(
                status: 'success',
                code: 200,
                message: 'Datos cargados correctamente',
                data: $department
            ));
        }
    }

    // DELETE
    function deleteDepartment()
    {
        $department = $this->DepartmentDAO->deleteDepartment($data);

        if (isset($department)) {
            return sendJsonResponse(new ApiResponse(
                status: 'success',
                code: 200,
                message: 'Departamento eliminado correctamente',
                data: $department
            ));
        }
    }
}

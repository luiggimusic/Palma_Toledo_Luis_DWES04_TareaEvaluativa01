<?php

declare(strict_types=1);

require_once '../app/models/Department.php'; // cargo el modelo
require_once '../app/helpers/arrayHelper.php'; // cargo el fichero con las funciones que me permitirán trabajar con los arrays


class DepartmentController
{
    function __construct() {}

    // GET
    function getAllDepartments()
    {
        $dataArray = Department::getAll();
        print_r($dataArray);
    }

    function getDepartmentById($id)
    {
        $success = Department::getById($id);
        if ($success) {
            echo "Status Code: 200 OK\nRegistro encontrado \n";
            print_r($success);
        } else {
            echo "Status Code: 409 Conflict\nNo se ha encontrado el departamento";
        }
    }

    // POST
    function createDepartment($data)
    {
        $departmentData = [
            'departmenId' => $data["departmenId"],
            'departmentName' => $data["departmentName"],
        ];

        // Llamo al método estático "create"
        $success = Department::create($departmentData);

        if ($success) {
            echo "Status Code: 201 OK\nDepartamento creado correctamente";
        } else {
            echo "Status Code: 409 Conflict\nNo se ha creado el departamento";
        }
    }

    // PUT
    function updateDepartment($id, $data)
    {
        $departmentData = [
            'id' => $data["id"],
            'departmenId' => $data["departmenId"],
            'departmentName' => $data["departmentName"],
        ];
        // Llamo al método estático "update" para actualizar
        $success = Department::update($id, $data);

        if ($success) {
            echo "Status Code: 204 OK\nDepartamento actualizado correctamente";
        } else {
            echo "Status Code: 409 Conflict\nError al actualizar";
        }
    }

    // DELETE
    function deleteDepartment($id)
    {
        $success = Department::delete($id);

        if ($success) {
            echo "Status Code: 204 OK\nDepartamento eliminado";
        } else {
            echo "Status Code: 409 Conflict\nError al eliminar";
        }
    }
}

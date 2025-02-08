<?php
class DepartmentService {
    public function createDepartmentObject($data) {
        // Creo instancia del modelo
        $department = new DepartmentEntity("","");

        // Uso los setters para actualizar los datos
        if (isset($data['departmentId'])) {
            $department->setDepartmentId($data['departmentId']);
        }
        if (isset($data['departmentName'])) {
            $department->setDepartmentName($data['departmentName']);
        }

        // Devuelvo el objeto
        return $department;
    }
}

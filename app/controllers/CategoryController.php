<?php

declare(strict_types=1);

require_once '../app/models/Category.php'; // cargo el modelo
require_once '../app/helpers/arrayHelper.php'; // cargo el fichero con las funciones que me permitirán trabajar con los arrays

class CategoryController
{
    function __construct() {}

    // GET
    function getAllCategories()
    {
        $dataArray = Category::getAll();
        print_r($dataArray);
    }

    function getCategoryById($id)
    {

        $success = Category::getById($id);
        if ($success) {
            echo "Status Code: 200 OK\nRegistro encontrado \n";
            print_r($success);
        } else {
            echo "Status Code: 409 Conflict\nNo se ha encontrado la categoría";
        }
    }

    // POST
    function createCategory($data)
    {
        $categoryData = [
            'categoryId' => $data["categoryId"],
            'categoryName' => $data["categoryName"],
        ];

        // Llamo al método estático "create"
        $success = Category::create($categoryData);

        if ($success) {
            echo "Status Code: 201 OK\nCategoria creada correctamente";
        } else {
            echo "Status Code: 409 Conflict\nNo se ha creado la categoría";
        }
    }

    // PUT
    function updateCategory($id, $data)
    {
        $categoryData = [
            'id' => $data["id"],
            'categoryId' => $data["categoryId"],
            'categoryName' => $data["categoryName"],
        ];
        // Llamo al método estático "update" para actualizar
        $success = Category::update($id, $data);

        if ($success) {
            echo "Status Code: 204 OK\nCategoría actualizada correctamente";
        } else {
            echo "Status Code: 409 Conflict\nError al actualizar";
        }
    }

    // DELETE
    function deleteCategory($id)
    {
        $success = Category::delete($id);

        if ($success) {
            echo "Status Code: 204 OK\nCategoría eliminada";
        } else {
            echo "Status Code: 409 Conflict\nError al eliminar";
        }
    }
}

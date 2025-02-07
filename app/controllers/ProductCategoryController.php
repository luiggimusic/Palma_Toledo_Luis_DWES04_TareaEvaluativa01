<?php

declare(strict_types=1);
header('Content-Type: application/json'); // le indico al cliente que la respuesta es de tipo JSON.

require_once '../app/models/DAO/ProductCategoryDAO.php'; // cargo el modelo
// require '../app/utils/ApiResponse.php';
require_once '../app/helpers/helper.php'; // cargo el fichero con las funciones que me permitirán trabajar con los arrays

class ProductCategoryController
{
    private $ProductCategoryDAO;

    function __construct() {
        $this->ProductCategoryDAO = new ProductCategoryDAO();
    }

    // GET
    function getAllProductCategories()
    {
        $categories = $this->ProductCategoryDAO->getAllProductCategories();

        if (isset($categories)) {
            return sendJsonResponse(new ApiResponse(
                status: 'success',
                code: 200,
                message: 'Datos cargados correctamente',
                data: $categories
            ));
        } else {
            return sendJsonResponse(new ApiResponse(
                status: 'not success',
                code: 500,
                message: 'Error al leer los datos',
                data: $categories
            ));
        }
        $categories = json_encode($categories);
        print_r($categories);
    }

    function getProductCategoryById($id)
    {
        $productCategory = $this->ProductCategoryDAO->getProductCategoryById($id);

        if (isset($productCategory)) {
            return sendJsonResponse(new ApiResponse(
                status: 'success',
                code: 200,
                message: 'Datos cargados correctamente',
                data: $productCategory
            ));
        } else {
            return sendJsonResponse(new ApiResponse(
                status: 'not success',
                code: 500,
                message: 'Categoría de producto no encontrada',
                data: $productCategory
            ));
        }
        $productCategory = json_encode($productCategory);
        print_r($productCategory);
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

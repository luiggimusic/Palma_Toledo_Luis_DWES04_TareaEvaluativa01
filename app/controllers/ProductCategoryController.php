<?php

declare(strict_types=1);
header('Content-Type: application/json'); // le indico al cliente que la respuesta es de tipo JSON.

require_once '../app/models/DAO/ProductCategoryDAO.php'; 
require_once '../app/helpers/helper.php'; 

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
    function createProductCategory($data)
    {
        $productCategory = $this->ProductCategoryDAO->createProductCategory($data);

        if (isset($productCategory)) {
            return sendJsonResponse(new ApiResponse(
                status: 'success',
                code: 200,
                message: 'Datos cargados correctamente',
                data: $productCategory
            ));
        }
    }

    // PUT
    function updateProductCategory($data)
    {
        $productCategory = $this->ProductCategoryDAO->updateProductCategory($data);

        if (isset($productCategory)) {
            return sendJsonResponse(new ApiResponse(
                status: 'success',
                code: 200,
                message: 'Datos cargados correctamente',
                data: $productCategory
            ));
        }
    }

    // DELETE
    function deleteProductCategory($data)
    {
        $productCategory = $this->ProductCategoryDAO->deleteProductCategory($data);

        if (isset($productCategory)) {
            return sendJsonResponse(new ApiResponse(
                status: 'success',
                code: 200,
                message: 'Categoría de producto eliminada correctamente',
                data: $productCategory
            ));
        }
    }
}

<?php

declare(strict_types=1);

header('Content-Type: application/json'); // le indico al cliente que la respuesta es de tipo JSON.
require_once '../app/models/DAO/ProductDAO.php';
require_once '../app/helpers/helper.php'; // cargo el fichero con las funciones que me permitirán trabajar con los arrays

class ProductController
{
    private $ProductDAO;

    function __construct()
    {
        $this->ProductDAO = new ProductDAO();
    }

    // GET
    function getAllProducts() {
        $products = $this->ProductDAO->getAllProducts();

        if (isset($products)) {
            return sendJsonResponse(new ApiResponse(
                status: 'success',
                code: 200,
                message: 'Datos cargados correctamente',
                data: $products
            ));
        } else {
            return sendJsonResponse(new ApiResponse(
                status: 'not success',
                code: 500,
                message: 'Error al leer los datos',
                data: $products
            ));
        }
        $products = json_encode($products);
        print_r($products);        
    }

    function getProductById($id) {
        $products = $this->ProductDAO->getProductById($id);

        if (isset($products)) {
            return sendJsonResponse(new ApiResponse(
                status: 'success',
                code: 200,
                message: 'Datos cargados correctamente',
                data: $products
            ));
        } else {
            return sendJsonResponse(new ApiResponse(
                status: 'not success',
                code: 500,
                message: 'Producto no encontrado',
                data: $products
            ));
        }
        $products = json_encode($products);
        print_r($products);   
    }

    // POST
    function createProduct($data) {
        $product = $this->ProductDAO->createProduct($data);

        if (isset($product)) {
            return sendJsonResponse(new ApiResponse(
                status: 'success',
                code: 200,
                message: 'Datos cargados correctamente',
                data: $product
            ));
        }        
    }

    // PUT
    function updateProduct($data) {
        $product = $this->ProductDAO->updateProduct($data);

        if (isset($product)) {
            return sendJsonResponse(new ApiResponse(
                status: 'success',
                code: 200,
                message: 'Datos cargados correctamente',
                data: $product
            ));
        } 
    }
    function deleteProduct($data) {
        $product = $this->ProductDAO->deleteProduct($data);

        if (isset($product)) {
            return sendJsonResponse(new ApiResponse(
                status: 'success',
                code: 200,
                message: 'Producto eliminado correctamente',
                data: $product
            ));
        }        
    }
}

<?php

declare(strict_types=1);

require_once '../app/models/Product.php'; // cargo el modelo
require_once '../app/helpers/arrayHelper.php'; // cargo el fichero con las funciones que me permitirán trabajar con los arrays


class ProductController
{
    function __construct() {}

    // GET
    function getAllProducts()
    {
        $dataArray = Product::getAll();
        print_r($dataArray);
    }

    function getProductById($id)
    {
        $success = Product::getById($id);
        if ($success) {
            echo "Status Code: 200 OK\nRegistro encontrado \n";
            print_r($success);
        } else {
            echo "Status Code: 409 Conflict\nNo se ha encontrado la categoría";
        }
    }

    // POST
    function createProduct($data)
    {
        $productData = [
            'productCode' => $data["productCode"],
            'productName' => $data["productName"],
            'batchNumber' => $data["batchNumber"],
            'location' => $data["location"],
            'quantity' => $data["quantity"],
            'category' => $data["category"],
        ];

        // Llamo al método estático "create"
        $success = Product::create($productData);

        if ($success) {
            echo "Status Code: 201 OK\nProducto creado correctamente";
        } else {
            echo "Status Code: 409 Conflict\nNo se ha creado el producto";
        }
    }

    // PUT
    function updateProduct($id, $data)
    {
        $productData = [
            'id' => $data["id"],
            'productCode' => $data["productCode"],
            'productName' => $data["productName"],
            'batchNumber' => $data["batchNumber"],
            'location' => $data["location"],
            'quantity' => $data["quantity"],
            'category' => $data["category"],
        ];



        // Llamo al método estático "update" para actualizar
        $success = Product::update($id, $data);

        if ($success) {
            echo "Status Code: 204 OK\nProducto actualizado correctamente";
        } else {
            echo "Status Code: 409 Conflict\nError al actualizar";
        }
    }
    function deleteProduct($id)
    {
        $success = Product::delete($id);
        if ($success) {
            echo "Status Code: 204 OK\nProducto eliminado";
        } else {
            echo "Status Code: 409 Conflict\nError al eliminar";
        }
    }
}

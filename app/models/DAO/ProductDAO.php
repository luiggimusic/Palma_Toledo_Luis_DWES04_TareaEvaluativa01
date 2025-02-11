<?php
require '../app/models/DTO/ProductDTO.php';
require '../app/models/entity/ProductEntity.php';
require '../app/services/ProductService.php';
class ProductDAO
{
    private $db;
    public function __construct()
    {
        $this->db = DatabaseSingleton::getInstance();
    }

    // CRUD
    // GET
    function getAllProducts()
    {
        $connection = $this->db->getConnection();
        $query = "SELECT * FROM products p JOIN productCategories c ON p.productCategoryId = c.productCategoryId";
        $statement = $connection->query($query);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        $productsDTO = [];
        foreach ($result as $product) {
            $productsDTO[] = new ProductDTO(
                $product['productCode'],
                $product['productName'],
                $product['productCategoryId'],
                $product['productCategoryName'],
            );
        }
        if ($productsDTO == null) {
            return null;
        } else {
            return $productsDTO;
        }
    }

    function getProductById($id)
    {
        $connection = $this->db->getConnection();
        $query = "SELECT * FROM products p JOIN productCategories c ON p.productCategoryId = c.productCategoryId WHERE p.id = $id";
        $statement = $connection->query($query);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        $productsDTO = [];
        foreach ($result as $product) {
            $productsDTO[] = new ProductDTO(
                $product['productCode'],
                $product['productName'],
                $product['productCategoryId'],
                $product['productCategoryName'],
            );
        }
        if ($productsDTO == null) {
            return null;
        } else {
            return $productsDTO;
        }
    }

    // POST
    function createProduct($data)
    {
        // Con userService creo un objeto para evitar código en los otros métodos
        $productService = new ProductService();

        $product = $productService->createProductObject($data);

        // Valido los datos antes de la inserción
        $errores = $product->validacionesDeProducto();

        $connection = $this->db->getConnection();

        // Si el código de producto ya existe, añadirá el mensaje de error
        if (productCodeVerify($connection, $data)) {
            $errores["productCode"] = 'El código de producto ya está registrado en el sistema';
        }

        // Verifico si productCategoryId está registrado en la tabla productCategories
        if (!Self::CategoryVerify($connection, $data)) {
            $errores["productCategoryId"] = 'La categoría de producto: ' . strtoupper($data['productCategoryId']) .
                ' no existe en el sistema';
        }

        if (empty($errores)) {
            $query = "INSERT INTO products (productCode, productName, productCategoryId) 
                  VALUES (:productCode, :productName, :productCategoryId)";
            $statement = $connection->prepare($query);
            $statement->execute([
                'productCode' => $product->getProductCode(),
                'productName' => $product->getProductName(),
                'productCategoryId' => $product->getProductCategoryId(),
            ]);

            // Obtengo los datos para mostrarlo en la respuesta
            $product = Self::showProductData($connection, $data);
            return $product;
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
    function updateProduct($data)
    {
        $productService = new ProductService();
        $product = $productService->createProductObject($data);

        $connection = $this->db->getConnection();

        // Valido los datos antes de la inserción
        // Si el productCode no existe, añadirá el mensaje de error
        $errores = $product->validacionesDeProducto();        
        if (!productCodeVerify($connection, $data)) {
            $errores["productCode"] = 'El código de producto no está registrado en el sistema';
        }
        // Verifico si productCategoryId está registrado en la tabla productCategories
        if (!Self::CategoryVerify($connection, $data)) {
            $errores["productCategoryId"] = 'La categoría de producto: ' . strtoupper($data['productCategoryId']) .
                ' no existe en el sistema';
        }

        if (empty($errores)) {
            $query = "UPDATE products SET productCode=:productCode, productName=:productName, productCategoryId=:productCategoryId WHERE productCode=:productCode";
            $statement = $connection->prepare($query);
            $statement->execute([
                'productCode' => $product->getProductCode(),
                'productName' => $product->getProductName(),
                'productCategoryId' => $product->getProductCategoryId(),
            ]);

            // Obtengo los datos del producto para mostrarlos en la respuesta
            $product = Self::showProductData($connection, $data);
            return $product;
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
    function deleteProduct($data)
    {
        $connection = $this->db->getConnection();

        // Obtengo los datos para mostrarlo en la respuesta, 
        // en este caso antes de eliminar la tupla
        $product = Self::showProductData($connection, $data);

        // Si el productCode no existe, añadirá el mensaje de error
        if (!productCodeVerify($connection, $data)) {
            $errores["productCode"] = 'El código de producto no está registrado en el sistema';
        }
   
        if (empty($errores)) {
            $query = "DELETE FROM products WHERE productCode=:productCode";
            $statement = $connection->prepare($query);
            $statement->bindParam(':productCode', $data['productCode'], PDO::PARAM_STR);
            $statement->execute();

            // Una vez ejecutada la eliminación, envío los datos del elemento eliminado para que se vean en la respuesta
            return $product;
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

    private function CategoryVerify($connection, $data)
    {
        // Verifico si el productCategoryId está registrado en la tabla productCategories
        $query = "SELECT COUNT(*) FROM productCategories WHERE productCategoryId = :productCategoryId";
        $statement = $connection->prepare($query);

        if ($statement) {
            $statement->execute(['productCategoryId' => $data['productCategoryId']]);
            $count = $statement->fetchColumn();
            return $count == 1;
        } else {
            // Controlo el error si la preparación de la consulta falla
            throw new Exception("Error al preparar la consulta SQL.");
        }
    }

    private function showProductData($connection, $data)
    {
        // Obtengo los datos del producto para mostrarlo en la respuesta
        $query = "SELECT * FROM products WHERE productCode = :productCode";
        $statement = $connection->prepare($query);
        $statement->execute(['productCode' => $data['productCode']]);
        $productData = $statement->fetch(PDO::FETCH_ASSOC);
        return $productData;
    }
}

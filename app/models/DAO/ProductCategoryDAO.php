<?php
// require '../app/core/DatabaseSingleton.php';
require '../app/models/DTO/ProductCategoryDTO.php';
require '../app/models/entity/ProductCategoryEntity.php';
require '../app/services/ProductCategoryService.php';

class ProductCategoryDAO
{
    private $db;
    public function __construct()
    {
        $this->db = DatabaseSingleton::getInstance();
    }

    // CRUD
    // GET
    function getAllProductCategories()
    {
        $connection = $this->db->getConnection();
        $query = "SELECT * FROM productcategories";
        $statement = $connection->query($query);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        $productCategoriesDTO = [];
        foreach ($result as $productCategory) {
            $productCategoriesDTO[] = new ProductCategoryDTO(
                $productCategory['productCategoryId'],
                $productCategory['productCategoryName'],
            );
        }
        if ($productCategoriesDTO == null) {
            return null;
        } else {
            return $productCategoriesDTO;
        }
    }

    function getProductCategoryById($id)
    {
        $connection = $this->db->getConnection();
        $query = "SELECT * FROM productcategories WHERE id = $id";
        $statement = $connection->query($query);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        $productCategoriesDTO = [];
        foreach ($result as $productCategory) {
            $productCategoriesDTO[] = new ProductCategoryDTO(
                $productCategory['productCategoryId'],
                $productCategory['productCategoryName'],
            );
        }
        if ($productCategoriesDTO == null) {
            return null;
        } else {
            return $productCategoriesDTO;
        }
    }

    // POST
    function createProductCategory($data)
    {
        // Creo un objeto que me permitirá dar la respuesta sin duplicar código
        $productCategoryService = new ProductCategoryService();

        $productCategory = $productCategoryService->createProductCategoryObject($data);

        // Valido los datos antes de la inserción
        $errores = $productCategory->validacionesDeProductCategory();

        $connection = $this->db->getConnection();

        // Si el productCategoryId ya existe, añadirá el mensaje de error
        if (Self::productCategoryIdVerify($connection, $data)) {
            $errores["productCategoryId"] = 'El ID de la categoría ya está registrado en el sistema';
        }        

        if (empty($errores)) {
            $query = "INSERT INTO productcategories (productCategoryId, productCategoryName) 
                  VALUES (:productCategoryId, :productCategoryName)";
            $statement = $connection->prepare($query);
            $statement->execute([
                'productCategoryId' => $productCategory->getProductCategoryId(),
                'productCategoryName' => $productCategory->getProductCategoryName(),
            ]);

            // Obtengo los datos para mostrarlo en la respuesta
            $productCategory = Self::showProductCategoryData($connection, $data);
            return $productCategory;
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
    function updateProductCategory($data)
    {
        $productCategoryService = new ProductCategoryService();
        $productCategory = $productCategoryService->createProductCategoryObject($data);

        // Valido los datos antes de la inserción
        $errores = $productCategory->validacionesDeProductCategory();
        $connection = $this->db->getConnection();

        // Si el productCategoryId no existe, añadirá el mensaje de error
        if (!Self::productCategoryIdVerify($connection, $data)) {
            $errores["productCategoryId"] = 'El ID de la categoría no está registrado en el sistema';
        }            

        if (empty($errores)) {
            $query = "UPDATE productcategories SET productCategoryId=:productCategoryId, productCategoryName=:productCategoryName WHERE productCategoryId=:productCategoryId";
            $statement = $connection->prepare($query);
            $statement->execute([
                'productCategoryId' => $productCategory->getProductCategoryId(),
                'productCategoryName' => $productCategory->getProductCategoryName(),
            ]);

            // Obtengo los datos del usuario para mostrarlo en la respuesta
            $productCategory = Self::showProductCategoryData($connection, $data);
            return $productCategory;
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
    function deleteProductCategory($data)
    {
        $connection = $this->db->getConnection();

        // Obtengo los datos para mostrarlo en la respuesta, 
        // en este caso antes de eliminar la tupla
        $productCategory = Self::showProductCategoryData($connection, $data);

         // Si el productCategoryId no existe, añadirá el mensaje de error
         if (!Self::productCategoryIdVerify($connection, $data)) {
            $errores["productCategoryId"] = 'El ID de la categoría no está registrado en el sistema';
        }   
        if (empty($errores)) {
            $query = "DELETE FROM productcategories WHERE productCategoryId=:productCategoryId";
            $statement = $connection->prepare($query);
            $statement->bindParam(':productCategoryId', $data['productCategoryId'], PDO::PARAM_STR);
            $statement->execute();

            // Una vez ejecutada la eliminación, envío los datos del elemento eliminado para que se vean en la respuesta
            return $productCategory;
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
    private function productCategoryIdVerify($connection, $data)
    {
        // Verifico si el productCategoryId está registrado en la tabla productcategories
        $query = "SELECT COUNT(*) FROM productcategories WHERE productCategoryId = :productCategoryId";
        $statement = $connection->prepare($query);
        $statement->execute(['productCategoryId' => $data['productCategoryId']]);
        $count = $statement->fetchColumn();
        if ($count == 1) {
            return true;
        }
    }
    private function showProductCategoryData($connection, $data)
    {
        // Obtengo los datos de la categoría actualizada para mostrarlo en la respuesta
        $query = "SELECT * FROM productcategories WHERE productCategoryId = :productCategoryId";
        $statement = $connection->prepare($query);
        $statement->execute(['productCategoryId' => $data['productCategoryId']]);
        $productCategoryData = $statement->fetch(PDO::FETCH_ASSOC);
        return $productCategoryData;
    }

}

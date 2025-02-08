<?php
class ProductService {
    public function createProductObject($data) {
        // Creo instancia del modelo
        $product = new ProductEntity("","","");

        // Uso los setters para actualizar los datos
        if (isset($data['productCode'])) {
            $product->setProductCode($data['productCode']);
        }
        if (isset($data['productName'])) {
            $product->setProductName($data['productName']);
        }
        if (isset($data['productCategoryId'])) {
            $product->setProductCategoryId($data['productCategoryId']);
        }

        // Devuelvo el objeto
        return $product;
    }
}

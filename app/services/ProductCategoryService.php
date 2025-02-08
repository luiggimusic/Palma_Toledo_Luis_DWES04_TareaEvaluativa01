<?php
class ProductCategoryService {
    public function createProductCategoryObject($data) {
        // Creo instancia del modelo
        $productCategory = new ProductCategoryEntity("","");

        // Uso los setters para actualizar los datos
        if (isset($data['productCategoryId'])) {
            $productCategory->setProductCategoryId($data['productCategoryId']);
        }
        if (isset($data['productCategoryName'])) {
            $productCategory->setProductCategoryName($data['productCategoryName']);
        }

        // Devuelvo el objeto
        return $productCategory;
    }
}

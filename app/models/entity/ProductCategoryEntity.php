<?php

class ProductCategoryEntity
{
    private string $productCategoryId;
    private string $productCategoryName;

    public function __construct(string $productCategoryId, string $productCategoryName)
    {
        $this->productCategoryId = $productCategoryId;
        $this->productCategoryName = $productCategoryName;
    }

    // Getters
    public function getProductCategoryId()
    {
        return $this->productCategoryId;
    }
    public function getProductCategoryName()
    {
        return $this->productCategoryName;
    }

    // Setters
    public function setProductCategoryId($productCategoryId)
    {
        $this->productCategoryId = $productCategoryId;
    }
    public function setProductCategoryName($productCategoryName)
    {
        $this->productCategoryName = $productCategoryName;
    }

    /*********** Funciones necesarias ***********/
    function validacionesDeProductCategory()
    {
        // Valido los datos insertados en body (formulario) y voy completando el array $arrayErrores con los errores que aparezcan
        $arrayErrores = array();
        if (empty($this->productCategoryId)) {
            $arrayErrores["productCategoryId"] = 'El ID de categoría es obligatorio';
        }
        if (empty($this->productCategoryName)) {
            $arrayErrores["productCategoryName"] = 'El nombre de la categoría de producto es obligatorio';
        }
        return $arrayErrores;
    }
}

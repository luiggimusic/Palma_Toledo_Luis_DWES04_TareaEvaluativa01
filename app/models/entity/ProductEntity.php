<?php

class ProductEntity
{
    private string $productCode;
    private string $productName;
    private string $productCategoryId;

    public function __construct(string $productCode, string $productName, string $productCategoryId)
    {
        $this->productCode = $productCode;
        $this->productName = $productName;
        $this->productCategoryId = $productCategoryId;
    }

    /**
     * Get the value of productCode
     */
    public function getProductCode(): string
    {
        return $this->productCode;
    }

    /**
     * Set the value of productCode
     */
    public function setProductCode(string $productCode): self
    {
        $this->productCode = strtoupper($productCode);

        return $this;
    }

    /**
     * Get the value of productNAme
     */
    public function getProductName(): string
    {
        return $this->productName;
    }

    /**
     * Set the value of productNAme
     */
    public function setProductName(string $productName): self
    {
        $this->productName = strtoupper($productName);

        return $this;
    }

    /**
     * Get the value of category
     */
    public function getProductCategoryId(): string
    {
        return $this->productCategoryId;
    }

    /**
     * Set the value of category
     */
    public function setProductCategoryId(string $productCategoryId): self
    {
        $this->productCategoryId = strtoupper($productCategoryId);

        return $this;
    }

    /*********** Funciones necesarias ***********/
    function validacionesDeProducto()
    {
        // Valido los datos insertados en body (formulario) y voy completando el array $arrayErrores con los errores que aparezcan
        $arrayErrores = array();
        if (empty($this->productCode)) {
            $arrayErrores["productCode"] = 'El código del producto es obligatorio';
        }
        if (empty($this->productName)) {
            $arrayErrores["productName"] = 'El nombre del producto es obligatorio';
        }
        if (empty($this->productCategoryId)) {
            $arrayErrores["productCategoryId"] = 'El ID de categoría del producto es obligatorio';
        }
        return $arrayErrores;
    }     
}

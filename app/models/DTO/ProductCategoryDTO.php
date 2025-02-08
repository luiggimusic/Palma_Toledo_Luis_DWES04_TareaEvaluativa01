<?php

class ProductCategoryDTO implements JsonSerializable{
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

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    } 
}
<?php

class ProductDTO implements JsonSerializable{
    private string $productCode;
    private string $productName;
    private string $productCategoryId;
    private string $productCategoryName;

    public function __construct(string $productCode, string $productName, string $productCategoryId,string $productCategoryName)
    {
        $this->productCode = $productCode;
        $this->productName = $productName;
        $this->productCategoryId = $productCategoryId;
        $this->productCategoryName = $productCategoryName;
    }
    // Getters
    public function getProductCode(): string
    {
        return $this->productCode;
    }
    public function getProductName(): string
    {
        return $this->productName;
    }
    public function getProductCategoryId(): string
    {
        return $this->productCategoryId;
    }
    public function getProductCategoryName(): string
    {
        return $this->productCategoryName;
    }

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    } 
}
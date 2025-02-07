<?php

class ProductCategoryDTO implements JsonSerializable{
    private string $categoryId;
    private string $categoryName;

    public function __construct(string $categoryId, string $categoryName)
    {
        $this->categoryId = $categoryId;
        $this->categoryName = $categoryName;
    }

    // Getters
    public function getCategoryId()
    {
        return $this->categoryId;
    }
    public function getCategoryName()
    {
        return $this->categoryName;
    }

    // Setters
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;
    }
    public function setCategoryName($categoryName)
    {
        $this->categoryName = $categoryName;
    }

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }    
}
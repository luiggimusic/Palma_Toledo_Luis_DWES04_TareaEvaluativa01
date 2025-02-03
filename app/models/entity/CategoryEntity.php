<?php

class CategoryEntity
{
    private int $id;
    private string $categoryId;
    private string $categoryName;

    // Constructor para inicializar propiedades

    public function __construct(int $id, string $categoryId, string $categoryName)
    {
        $this->id = $id;
        $this->categoryId = $categoryId;
        $this->categoryName = $categoryName;
    }


    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of categoryId
     */
    public function getCategoryId(): string
    {
        return $this->categoryId;
    }

    /**
     * Set the value of categoryId
     */
    public function setCategoryId(string $categoryId): self
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    /**
     * Get the value of categoryName
     */
    public function getCategoryName(): string
    {
        return $this->categoryName;
    }

    /**
     * Set the value of categoryName
     */
    public function setCategoryName(string $categoryName): self
    {
        $this->categoryName = $categoryName;

        return $this;
    }
}

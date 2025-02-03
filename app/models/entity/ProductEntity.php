<?php

class ProductEntity
{
    private int $id;
    private string $productCode;
    private string $productNAme;
    private string $batchNumber;
    private string $location;
    private int $quantity;
    private string $category;

    // Constructor para inicializar propiedades

    public function __construct(int $id, string $productCode, string $productNAme, string $batchNumber, string $location, int $quantity, string $category)
    {
        $this->id = $id;
        $this->productCode = $productCode;
        $this->productNAme = $productNAme;
        $this->batchNumber = $batchNumber;
        $this->location = $location;
        $this->quantity = $quantity;
        $this->category = $category;
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
        $this->productCode = $productCode;

        return $this;
    }

    /**
     * Get the value of productNAme
     */
    public function getProductNAme(): string
    {
        return $this->productNAme;
    }

    /**
     * Set the value of productNAme
     */
    public function setProductNAme(string $productNAme): self
    {
        $this->productNAme = $productNAme;

        return $this;
    }

    /**
     * Get the value of batchNumber
     */
    public function getBatchNumber(): string
    {
        return $this->batchNumber;
    }

    /**
     * Set the value of batchNumber
     */
    public function setBatchNumber(string $batchNumber): self
    {
        $this->batchNumber = $batchNumber;

        return $this;
    }

    /**
     * Get the value of location
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * Set the value of location
     */
    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get the value of quantity
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * Set the value of quantity
     */
    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get the value of category
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * Set the value of category
     */
    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }
}

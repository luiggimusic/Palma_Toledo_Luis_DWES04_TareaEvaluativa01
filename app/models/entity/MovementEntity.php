<?php

class MovementEntity
{
    private int $id;
    private string $productCode;
    private string $productNAme;
    private string $fromBatchNumber;
    private string $toBatchNumber;
    private string $fromLocation;
    private string $toLocation;
    private int $quantity;
    private string $movementId;
    private string $movementDate;
    private string $customer;
    private string $supplier;

    // Constructor para inicializar propiedades

    public function __construct(
        int $id,
        string $productCode,
        string $productNAme,
        string $fromBatchNumber,
        string $toBatchNumber,
        string $fromLocation,
        string $toLocation,
        int $quantity,
        string $movementId,
        string $movementDate,
        string $customer,
        string $supplier,
    ) {
        $this->id = $id;
        $this->productCode = $productCode;
        $this->productNAme = $productNAme;
        $this->fromBatchNumber = $fromBatchNumber;
        $this->toBatchNumber = $toBatchNumber;
        $this->fromLocation = $fromLocation;
        $this->toLocation = $toLocation;
        $this->quantity = $quantity;
        $this->movementId = $movementId;
        $this->movementDate = $movementDate;
        $this->customer = $customer;
        $this->supplier = $supplier;
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
     * Get the value of fromBatchNumber
     */
    public function getFromBatchNumber(): string
    {
        return $this->fromBatchNumber;
    }

    /**
     * Set the value of fromBatchNumber
     */
    public function setFromBatchNumber(string $fromBatchNumber): self
    {
        $this->fromBatchNumber = $fromBatchNumber;

        return $this;
    }

    /**
     * Get the value of toBatchNumber
     */
    public function getToBatchNumber(): string
    {
        return $this->toBatchNumber;
    }

    /**
     * Set the value of toBatchNumber
     */
    public function setToBatchNumber(string $toBatchNumber): self
    {
        $this->toBatchNumber = $toBatchNumber;

        return $this;
    }

    /**
     * Get the value of fromLocation
     */
    public function getFromLocation(): string
    {
        return $this->fromLocation;
    }

    /**
     * Set the value of fromLocation
     */
    public function setFromLocation(string $fromLocation): self
    {
        $this->fromLocation = $fromLocation;

        return $this;
    }

    /**
     * Get the value of toLocation
     */
    public function getToLocation(): string
    {
        return $this->toLocation;
    }

    /**
     * Set the value of toLocation
     */
    public function setToLocation(string $toLocation): self
    {
        $this->toLocation = $toLocation;

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
     * Get the value of movementId
     */
    public function getMovementId(): string
    {
        return $this->movementId;
    }

    /**
     * Set the value of movementId
     */
    public function setMovementId(string $movementId): self
    {
        $this->movementId = $movementId;

        return $this;
    }

    /**
     * Get the value of movementDate
     */
    public function getMovementDate(): string
    {
        return $this->movementDate;
    }

    /**
     * Set the value of movementDate
     */
    public function setMovementDate(string $movementDate): self
    {
        $this->movementDate = $movementDate;

        return $this;
    }

    /**
     * Get the value of customer
     */
    public function getCustomer(): string
    {
        return $this->customer;
    }

    /**
     * Set the value of customer
     */
    public function setCustomer(string $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get the value of supplier
     */
    public function getSupplier(): string
    {
        return $this->supplier;
    }

    /**
     * Set the value of supplier
     */
    public function setSupplier(string $supplier): self
    {
        $this->supplier = $supplier;

        return $this;
    }
}












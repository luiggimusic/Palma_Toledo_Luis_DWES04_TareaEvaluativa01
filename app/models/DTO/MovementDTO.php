<?php

class MovementDTO implements JsonSerializable
{
    private string $productCode;
    private string $productName;
    private string $fromBatchNumber;
    private string $toBatchNumber;
    private string $fromLocation;
    private string $toLocation;
    private int $quantity;
    private string $movementTypeId;
    private string $movementTypeName;
    private string $movementDate;
    private string $customer;
    private string $supplier;

    public function __construct(
        string $productCode,
        string $productName,
        string $fromBatchNumber,
        string $toBatchNumber,
        string $fromLocation,
        string $toLocation,
        int $quantity,
        string $movementTypeId,
        string $movementTypeName,
        string $movementDate,
        string $customer,
        string $supplier,
    ) {
        $this->productCode = $productCode;
        $this->productName = $productName;
        $this->fromBatchNumber = $fromBatchNumber;
        $this->toBatchNumber = $toBatchNumber;
        $this->fromLocation = $fromLocation;
        $this->toLocation = $toLocation;
        $this->quantity = $quantity;
        $this->movementTypeId = $movementTypeId;
        $this->movementTypeName = $movementTypeName;
        $this->movementDate = $movementDate;
        $this->customer = $customer;
        $this->supplier = $supplier;
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
     * Get the value of productName
     */
    public function getProductName(): string
    {
        return $this->productName;
    }

    /**
     * Set the value of productName
     */
    public function setProductName(string $productName): self
    {
        $this->productName = $productName;

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
     * Get the value of movementTypeId
     */
    public function getMovementTypeId(): string
    {
        return $this->movementTypeId;
    }

    /**
     * Set the value of movementTypeId
     */
    public function setMovementTypeId(string $movementTypeId): self
    {
        $this->movementTypeId = $movementTypeId;

        return $this;
    }

    /**
     * Get the value of movementTypeName
     */
    public function getMovementTypeName(): string
    {
        return $this->movementTypeName;
    }

    /**
     * Set the value of movementTypeName
     */
    public function setMovementTypeName(string $movementTypeName): self
    {
        $this->movementTypeName = $movementTypeName;

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

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}

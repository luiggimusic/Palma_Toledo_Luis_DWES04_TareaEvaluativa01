<?php

class MovementType
{
    private int $id;
    private string $movementId;
    private string $movementName;

    // Constructor para inicializar propiedades

    public function __construct(int $id, string $movementId, string $movementName)
    {
        $this->id = $id;
        $this->movementId = $movementId;
        $this->movementName = $movementName;
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
     * Get the value of movementName
     */
    public function getMovementName(): string
    {
        return $this->movementName;
    }

    /**
     * Set the value of movementName
     */
    public function setMovementName(string $movementName): self
    {
        $this->movementName = $movementName;

        return $this;
    }
}

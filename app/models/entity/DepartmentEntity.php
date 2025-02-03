<?php

class DepartmentEntity
{
    private int $id;
    private string $departmenId;
    private string $departmentName;

    // Constructor para inicializar propiedades

    public function __construct(int $id, string $departmenId, string $departmentName)
    {
        $this->id = $id;
        $this->departmenId = $departmenId;
        $this->departmentName = $departmentName;
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
     * Get the value of departmenId
     */
    public function getDepartmenId(): string
    {
        return $this->departmenId;
    }

    /**
     * Set the value of departmenId
     */
    public function setDepartmenId(string $departmenId): self
    {
        $this->departmenId = $departmenId;

        return $this;
    }

    /**
     * Get the value of departmentName
     */
    public function getDepartmentName(): string
    {
        return $this->departmentName;
    }

    /**
     * Set the value of departmentName
     */
    public function setDepartmentName(string $departmentName): self
    {
        $this->departmentName = $departmentName;

        return $this;
    }
}

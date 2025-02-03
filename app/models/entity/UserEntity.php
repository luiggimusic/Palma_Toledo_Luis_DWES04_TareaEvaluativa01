<?php

class User
{
    private int $id;
    private string $name;
    private string $surname;
    private string $dni;
    private string $dateOfBirth;
    private string $department;

    // Constructor para inicializar propiedades

    public function __construct(int $id, string $name, string $surname, string $dni, string $dateOfBirth, string $department)
    {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->dni = $dni;
        $this->dateOfBirth = $dateOfBirth;
        $this->department = $department;
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
     * Get the value of name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of surname
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * Set the value of surname
     */
    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get the value of dni
     */
    public function getDni(): string
    {
        return $this->dni;
    }

    /**
     * Set the value of dni
     */
    public function setDni(string $dni): self
    {
        $this->dni = $dni;

        return $this;
    }

    /**
     * Get the value of dateOfBirth
     */
    public function getDateOfBirth(): string
    {
        return $this->dateOfBirth;
    }

    /**
     * Set the value of dateOfBirth
     */
    public function setDateOfBirth(string $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * Get the value of department
     */
    public function getDepartment(): string
    {
        return $this->department;
    }

    /**
     * Set the value of department
     */
    public function setDepartment(string $department): self
    {
        $this->department = $department;

        return $this;
    }
}

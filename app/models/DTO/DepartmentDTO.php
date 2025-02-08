<?php

class DepartmentDTO implements JsonSerializable{
    private string $departmentId;
    private string $departmentName;

    public function __construct(string $departmentId, string $departmentName)
    {
        $this->departmentId = $departmentId;
        $this->departmentName = $departmentName;
    }

    // Getters
    public function getDepartmentId()
    {
        return $this->departmentId;
    }
    public function getDepartmentName()
    {
        return $this->departmentName;
    }

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    } 
}
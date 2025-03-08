<?php

interface IBaseRepository
{
    public function GetAllList(): array;
    public function GetById(int $id);
    public function Add($entity);
    public function Update($id, $entity): bool;
    public function Delete(int $id): bool;
    public function GetFinalGrade(): array;
}

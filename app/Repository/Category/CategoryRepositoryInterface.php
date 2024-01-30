<?php

namespace App\Repository\Category;

interface CategoryRepositoryInterface
{
    public function create(array $data);
    public function getCategory();
    // public function getCategoryById($id);
    public function updateCategory($category);
    public function deleteCategory($id);
}

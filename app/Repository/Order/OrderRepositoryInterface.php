<?php

namespace App\Repository\Order;

interface OrderRepositoryInterface
{
    public function create(array $data);
    public function getCategory();
    public function getCategoryById(int $id);
    public function updateCategory($category);
    public function deleteCategory($id);
}

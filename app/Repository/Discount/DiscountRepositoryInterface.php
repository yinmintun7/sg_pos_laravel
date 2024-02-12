<?php

namespace App\Repository\Discount;

interface DiscountRepositoryInterface
{
    public function create(array $data);
    public function getDiscount();
    public function getDiscountById(int $id);
    // public function updateCategory($category);
    public function delete($id);
}

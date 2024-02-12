<?php

namespace App\Repository\Discount;

interface DiscountRepositoryInterface
{
    public function create(array $data);
    public function getDiscount();
    public function getDiscountById(int $id);
    public function getItemByDiscountId(int $id);
    public function update($discount);
    public function delete($id);
}

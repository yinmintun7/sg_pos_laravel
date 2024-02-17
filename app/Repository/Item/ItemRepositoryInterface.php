<?php

namespace App\Repository\Item;

interface ItemRepositoryInterface
{
    public function create(array $data);
    public function getItems(bool $api = false);
    public function getItemByCategory(int $category_id, bool $api = false);
    public function getOrderItemById(int $item_id);
    // // public function getCategoryById($id);
    public function updateItem($item);
    public function deleteItem($id);
}

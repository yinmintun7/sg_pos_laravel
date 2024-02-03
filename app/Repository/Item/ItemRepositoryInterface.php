<?php

namespace App\Repository\Item;

interface ItemRepositoryInterface
{
    public function create(array $data);
    public function getItems();
    // // public function getCategoryById($id);
    public function updateItem($item);
    public function deleteItem($id);
}

<?php

namespace App\Repository\Item;

interface ItemRepositoryInterface
{
    public function create(array $data);
    public function getItems(bool $api = false);
    public function getItemByCategory(int $category_id, bool $api = false);
    public function getOrderItemById(int $item_id);
    public function storeOrderItems(array $order_items);
    public function getOrderList(int $shift_id);
    public function CancelOrder(array $order);
    // // public function getCategoryById($id);
    public function updateItem($item);
    public function deleteItem($id);
}

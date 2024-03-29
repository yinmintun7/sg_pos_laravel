<?php

namespace App\Repository\Order;

interface OrderRepositoryInterface
{
    public function getOrderItemById(int $item_id);
    public function storeOrderItems(array $order_items);
    public function getOrderList(int $shift_id);
    public function CancelOrder(array $order);
    // public function getEditOrder(int $order_id);
    public function getOrderItems(int $id);
    public function updateOrder(array $data);
    public function getOrderDetail(array $data);
    public function insertPayOrder(array $data);
    public function getSettingData();
}

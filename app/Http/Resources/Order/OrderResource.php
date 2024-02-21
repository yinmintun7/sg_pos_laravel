<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'created_at'    => $this->created_at,
            'total_amount'  => $this->total_amount,
            'order_no'      => $this->order_no,
            'status'        => $this->status,
            'order_detail'  => $this->when($this->getOrderDetail() != null,
                               OrderDetailResource::collection($this->getOrderDetail)
                               )
    ];
    }
}

<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Item\ItemResource;

class OrderDetailResource extends JsonResource
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
            'id'             => $this->id,
            'status'         => $this->status,
            'quantity'       => $this->quantity,
            'sub_total'      => $this->sub_total,
            'order_id'       => $this->order_id,
            'discount_price' => $this->discount_price,
            'original_price' => $this->original_price,
            'item'           => $this->when($this->getItemFromOrderDeatil() != null,
                                new ItemResource($this->getItemFromOrderDeatil)
            )

    ];
    }
}

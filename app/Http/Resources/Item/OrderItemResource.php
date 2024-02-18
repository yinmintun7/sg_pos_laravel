<?php

namespace App\Http\Resources\Item;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
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
            'id'                => $this->id,
            'name'              => $this->name,
            'category_id'       => $this->category_id,
            'image'             => $this->image,
            'price'             => $this->price,
            'code_no'           => $this->code_no,
            'discount'          => $this->discount,
            'quantity'          => $this->quantity,
            'original_discount' => $this->original_discount,
            'amount'            => $this->amount,
            'original_amount'   => $this->original_amount,
        ];
    }
}

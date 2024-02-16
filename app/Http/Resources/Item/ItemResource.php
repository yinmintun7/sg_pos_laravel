<?php

namespace App\Http\Resources\Item;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
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
            'id'           => $this->id,
            'name'         => $this->name,
            'category_id'  => $this->category_id,
            'price'        => $this->price,
            'quantity'     => $this->quantity,
            'image'        => $this->image,
            // 'item'       =>when($this->getItems() != null){
            //                I
            // }
        ];
    }
}

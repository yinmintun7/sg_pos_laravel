<?php

namespace App\Http\Resources\Setting;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
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
            'id'              => $this->id,
            'company_name'    => $this->company_name,
            'company_phone'   => $this->company_phone,
            'company_email'   => $this->company_address,
            'company_logo'    => $this->company_logo,
    ];
    }
}

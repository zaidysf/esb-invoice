<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Item extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [
            'type' => $this->item_type->name,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'currency' => config('global.default_currency_symbol'),
        ];
        return $data;
    }
}

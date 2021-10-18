<?php

namespace App\Http\Resources;

use App\Http\Resources\Item;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceItem extends JsonResource
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
            'item' => new Item($this->item),
            'qty' => $this->qty,
            'price' => $this->price,
            'currency' => config('global.default_currency_symbol'),
        ];
        return $data;
    }
}

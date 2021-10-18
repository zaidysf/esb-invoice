<?php

namespace App\Http\Resources;

use App\Models\Setting;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use App\Http\Resources\InvoiceItem;

class Invoice extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'invoice_no' => Str::padLeft($this->id, config('global.invoice_digits'), 0),
            'issue_date' => date('d/m/Y', strtotime($this->issued_at)),
            'due_date' => date('d/m/Y', strtotime($this->due_date)),
            'subject' => $this->subject,
            'seller' => Setting::where('key', 'LIKE', 'company_%')->get()->pluck('value', 'key'),
            'buyer' => new Client($this->client),
            'items' => InvoiceItem::collection($this->invoice_items)
        ];
        return $data;
    }
}

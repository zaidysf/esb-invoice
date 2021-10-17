<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InvoiceItem
 *
 * @property int $id
 * @property int $invoice_id
 * @property int $item_id
 * @property float $qty
 * @property float $price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Invoice $invoice
 * @property Item $item
 *
 * @package App\Models
 */
class InvoiceItem extends Model
{
	protected $table = 'invoice_items';

	protected $casts = [
		'invoice_id' => 'int',
		'item_id' => 'int',
		'qty' => 'float',
		'price' => 'float'
	];

	protected $fillable = [
		'invoice_id',
		'item_id',
		'qty',
		'price'
	];

	public function invoice()
	{
		return $this->belongsTo(Invoice::class);
	}

	public function item()
	{
		return $this->belongsTo(Item::class);
	}

    public static function boot()
    {
        parent::boot();

        self::creating(function($model){
            $model->created_at = date('Y-m-d H:i:s');
            $model->updated_at = date('Y-m-d H:i:s');
        });

        self::updating(function($model){
            $model->updated_at = date('Y-m-d H:i:s');
        });
    }
}

<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Item
 *
 * @property int $id
 * @property int $item_type_id
 * @property string $name
 * @property string|null $description
 * @property float $price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property ItemType $item_type
 * @property Collection|Invoice[] $invoices
 *
 * @package App\Models
 */
class Item extends Model
{
	protected $table = 'items';

	protected $casts = [
		'item_type_id' => 'int',
		'price' => 'float'
	];

	protected $fillable = [
		'item_type_id',
		'name',
		'description',
		'price'
	];

	public function item_type()
	{
		return $this->belongsTo(ItemType::class);
	}

	public function invoices()
	{
		return $this->belongsToMany(Invoice::class, 'invoice_items')
					->withPivot('id', 'qty', 'price')
					->withTimestamps();
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

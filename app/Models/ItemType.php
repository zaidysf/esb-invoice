<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ItemType
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|Item[] $items
 *
 * @package App\Models
 */
class ItemType extends Model
{
	protected $table = 'item_types';

	protected $fillable = [
		'name'
	];

	public function items()
	{
		return $this->hasMany(Item::class);
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

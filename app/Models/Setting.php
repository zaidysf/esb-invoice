<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Setting
 *
 * @property int $id
 * @property string $key
 * @property string|null $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Setting extends Model
{
	protected $table = 'settings';

	protected $fillable = [
		'key',
		'value'
	];

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

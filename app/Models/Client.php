<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Client
 *
 * @property int $id
 * @property string $name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $address1
 * @property string|null $address2
 * @property string $country
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|Invoice[] $invoices
 *
 * @package App\Models
 */
class Client extends Model
{
	protected $table = 'clients';

	protected $fillable = [
		'name',
		'email',
		'phone',
		'address1',
		'address2',
		'country'
	];

	public function invoices()
	{
		return $this->hasMany(Invoice::class);
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

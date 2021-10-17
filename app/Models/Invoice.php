<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Invoice
 *
 * @property int $id
 * @property int $client_id
 * @property Carbon $issued_at
 * @property Carbon $due_date
 * @property string $subject
 * @property int $is_using_tax
 * @property float $payments
 * @property int $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Client $client
 * @property Collection|Item[] $items
 *
 * @package App\Models
 */
class Invoice extends Model
{
	protected $table = 'invoices';

	protected $casts = [
		'client_id' => 'int',
		'is_using_tax' => 'int',
		'payments' => 'float',
		'status' => 'int'
	];

	protected $dates = [
		'issued_at',
		'due_date'
	];

	protected $fillable = [
		'client_id',
		'issued_at',
		'due_date',
		'subject',
		'is_using_tax',
		'payments',
		'status'
	];

    public $statusArr = [
        0 => 'Pending',
        1 => 'Paid'
    ];

	public function client()
	{
		return $this->belongsTo(Client::class);
	}

	public function items()
	{
		return $this->belongsToMany(Item::class, 'invoice_items')
					->withPivot('id', 'qty', 'price')
					->withTimestamps();
	}

    public function invoice_items()
	{
		return $this->hasMany(InvoiceItem::class);
	}

    public function getStatusNameAttribute()
    {
        return $this->statusArr[$this->status];
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    public $incrementing = false;
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['id', 'nif', 'address', 'default_payment_type', 'default_payment_ref', 'custom'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }

    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class, 'customer_id', 'id');
    }

    public function tshirtImages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TshirtImage::class, 'customer_id', 'id');
    }
}

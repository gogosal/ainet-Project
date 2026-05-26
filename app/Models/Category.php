<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;
    public $timestamps = false;
    protected $fillable = ['name', 'image_url', 'custom'];

    public function tshirtImages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TshirtImage::class);
    }
}

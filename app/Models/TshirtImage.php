<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class TshirtImage extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['customer_id', 'category_id', 'name', 'description', 'image_url', 'custom'];

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function isCatalog(): bool
    {
        return is_null($this->customer_id);
    }

    public function getFullImageUrlAttribute(): string
    {
        if ($this->isCatalog()) {
            return asset('storage/tshirt_images/' . $this->image_url);
        } else {
            return route('private-image', ['filename' => $this->image_url]);
        }
    }
}

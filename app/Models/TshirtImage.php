<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

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

    public function getResolvedUrlAttribute()
    {
        $bare = basename($this->image_url);

        if (Str::startsWith($this->image_url, 'tshirt_images_private/') || Str::startsWith($this->image_url, 'tshirt_images_private')) {
            return route('private-image', $bare);
        } elseif (str_contains($this->image_url, '/')) {
            return asset('storage/' . $this->image_url);
        }

        return asset('storage/tshirt_images/' . $bare);
    }

    public function isCatalog(): bool
    {
        return is_null($this->customer_id);
    }
}

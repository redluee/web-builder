<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Image extends Model
{
    use HasFactory;

    protected $table = 'image';

    protected $fillable = [
        'name',
        'url',
        'alt_text',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'id';
    }

    public function pages()
    {
        return $this->belongsToMany(Page::class, 'page_images')->withTimestamps()->withPivot('order');
    }
}
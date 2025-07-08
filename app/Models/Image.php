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

    public function pages()
    {
        return $this->belongsToMany(Page::class, 'page_images')->withTimestamps()->withPivot('order');
    }
}
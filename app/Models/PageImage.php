<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageImage extends Model
{
    protected $table = 'page_images';

    protected $fillable = [
        'page_id',
        'image_id',
        'order',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }
}
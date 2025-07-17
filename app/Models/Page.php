<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Page extends Model
{
    use HasFactory;

    protected $table = 'page';

    protected $fillable = [
        'title',
        'slug',
    ];

    public function images()
    {
        return $this->belongsToMany(Image::class, 'page_images')->withTimestamps()->withPivot('order');
    }

    public function textblocks()
    {
        return $this->belongsToMany(Textblock::class, 'page_textblocks')->withTimestamps()->withPivot('order');
    }

    public function elements()
    {
        return $this->belongsToMany(Element::class, 'page_elements')
            ->withPivot('sort_order', 'settings')
            ->orderBy('pivot_sort_order')
            ->withTimestamps();
    }

    public function pageElements()
    {
        return $this->hasMany(PageElement::class);
    }
}
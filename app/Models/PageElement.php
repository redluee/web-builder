<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageElement extends Model
{
    protected $table = 'page_elements';

    protected $fillable = [
        'page_id',
        'element_id',
        'sort_order',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function element()
    {
        return $this->belongsTo(Element::class);
    }
}

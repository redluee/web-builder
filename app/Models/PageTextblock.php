<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageTextblock extends Model
{
    protected $table = 'page_textblocks';

    protected $fillable = [
        'page_id',
        'textblock_id',
        'order',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function textblock()
    {
        return $this->belongsTo(Textblock::class);
    }
}
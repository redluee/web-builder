<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Textblock extends Model
{
    use HasFactory;

    protected $table = 'textblock';

    protected $fillable = [
        'name',
        'content',
    ];

    public function pages()
    {
        return $this->belongsToMany(Page::class, 'page_textblocks')->withTimestamps()->withPivot('order');
    }
}
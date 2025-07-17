<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Element extends Model
{
    use HasFactory;

    protected $table = 'element';

    protected $fillable = [
        'name',
        'view_path',
        'label',
        'settings',
    ];

    public function pages()
    {
        return $this->belongsToMany(Page::class, 'page_elements')
            ->withPivot('sort_order', 'settings')
            ->withTimestamps();
    }
}

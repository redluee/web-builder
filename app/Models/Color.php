<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Color extends Model
{
    use HasFactory;

    protected $table = 'color';

    protected $fillable = [
        'variable_name',
        'hex_code',
    ];
}
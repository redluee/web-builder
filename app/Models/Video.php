<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $table = 'video';

    protected $fillable = [
        'youtube_url',
        'video_name',
        'video_description',
    ];
}
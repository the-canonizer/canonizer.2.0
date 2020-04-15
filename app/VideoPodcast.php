<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoPodcast extends Model
{
    protected $table = 'videopodcast';
    // Fillable Columns

    protected $fillable = ['id', 'html_content', 'created_at','updated_at'];

    
}

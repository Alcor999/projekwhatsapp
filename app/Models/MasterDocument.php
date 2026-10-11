<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterDocument extends Model
{
    protected $fillable = [
        'title',
        'file_path',
        'original_name',
    ];
}

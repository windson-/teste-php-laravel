<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $table = 'documents';

    protected $fillable = [
        'category_id',
        'title',
        'contents'
    ];

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id', 'id');
    }
}

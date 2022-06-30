<?php

namespace App\Models;

use App\Scopes\Own;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = [
        'title',
        'body',
        'cover_image',
        'path',
        'pinned'
    ];

    public function tags(){
        return $this->belongsToMany(Tag::class);
    }
    protected static function booted()
    {
        static::addGlobalScope(new Own());
    }
}

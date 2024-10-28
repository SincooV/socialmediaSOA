<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str; 
class Post extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'posts';
    protected $fillable = [
        'id',
        'title',
        'description',
        'user_id',
        'post_image'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (!$post->id) {
                $post->id = (string) Str::uuid();
            }
        });
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
  
}

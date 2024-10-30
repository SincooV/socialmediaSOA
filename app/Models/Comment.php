<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Comment extends Model
{
    use HasFactory;

    // Atributos que podem ser atribuídos em massa
    protected $fillable = ['post_id', 'user_id', 'content'];

    // Configurações de chave primária
    protected $keyType = 'string';
    public $incrementing = false;

    // Boot method to generate UUID
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Gera um UUID se o ID ainda não estiver definido
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    // Relacionamento com o modelo Post
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // Relacionamento com o modelo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

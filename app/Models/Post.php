<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'text', 'user_id', 'active'];

    
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function toggleActivity()
    {
        $this->active = !$this->active;
        $this->save();
    }
}

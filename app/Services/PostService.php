<?php
namespace App\Services;


use Illuminate\Database\Eloquent\ModelNotFoundException;

class PostService
{

    public function search($id)
    {
        $post = \App\Models\Post::find($id);
        if (!$post) {
            throw new ModelNotFoundException('User not found by ID ' . $id);
        }
        return $post;
    }

}
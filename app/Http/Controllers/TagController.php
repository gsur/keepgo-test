<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagAttachRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function attach(TagAttachRequest $request, Post $post) {

        $post->tags()->syncWithoutDetaching($request->validated( 'tags' ));

        $post->load('tags');

        return new PostResource($post);
    }

    public function detach(Post $post, Tag $tag) {

        $post->tags()->detach($tag->id);

        $post->load('tags');

        return new PostResource($post);
    }
}

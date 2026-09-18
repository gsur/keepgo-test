<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagAttachRequest;
use App\Http\Requests\TagStoreRequest;
use App\Http\Requests\TagUpdateRequest;
use App\Http\Resources\PostResource;
use App\Http\Resources\TagResource;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

    public function index(Tag $tag) {

        $tags = Tag::all();

        return TagResource::collection($tags);
    }

    public function store(TagStoreRequest $request, Tag $tag) {

        $tag = Tag::create($request->validated());

        return (new TagResource($tag));
    }

    public function update(TagUpdateRequest $request, Tag $tag ) {

        $tag->update($request->validated());

        return new TagResource($tag->fresh());
    }

    public function destroy(Tag $tag) {

        $tag->delete();

        return response()->json([
            'message' => 'Tag deleted successfully.',
        ]);
    }
}

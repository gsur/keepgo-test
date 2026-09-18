<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTagTest extends TestCase
{

    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_can_attach_tags_to_post(): void
    {
        $post = Post::factory()->for($this->user)->create();
        $tags = Tag::factory()->count(3)->create();

        $response = $this->postJson("/api/posts/{$post->id}/tags", [
            'tags' => $tags->pluck('id')->all(),
        ]);

        $response->assertOk()->assertJsonCount(3, 'data.tags');
    }

    public function test_can_detach_tag_from_post(): void
    {
        $post = Post::factory()->for($this->user)->create();
        $tag  = Tag::factory()->create();
        $otherTag = Tag::factory()->create();

        $post->tags()->attach([$tag->id, $otherTag->id]);

        $response = $this->deleteJson("/api/posts/{$post->id}/tags/{$tag->id}");

        $response->assertOk()
            ->assertJsonPath('data.id', $post->id)
            ->assertJsonCount(1, 'data.tags')
            ->assertJsonPath('data.tags.0.id', $otherTag->id);

        $this->assertNotContains(
            $tag->id,
            collect($response->json('data.tags'))->pluck('id')->all()
        );
    }

    public function test_can_show_post_with_tags(): void
    {
        $post = Post::factory()
            ->for($this->user)
            ->hasAttached(Tag::factory()->count(3))
            ->create();

        $response = $this->getJson("/api/posts/{$post->id}");

        $response->assertOk()
            ->assertJsonPath('data.id', $post->id)
            ->assertJsonPath('data.title', $post->title)
            ->assertJsonCount(3, 'data.tags');

//        $this->assertEqualsCanonicalizing(
//            $post->tags()->pluck('tags.id')->all(),
//            collect($response->json('data.tags'))->pluck('id')->all()
//        );
    }
}

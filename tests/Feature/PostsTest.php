<?php

namespace Tests\Feature;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\TestCase;

class PostsTest extends TestCase
{
    use RefreshDatabase;

    public function test_no_post_success()
    {
        $response = $this->get(route('posts.index'));
        $response->assertSeeText('No post yet!');
        $response->assertStatus(200);
    }

    public function test_1post_success()
    {
        $post = Post::create([
            'title' => 'this is a valid title',
            'content' => 'this is a valid content we will refactore this using factories'
        ]);

        $this->assertDatabaseHas('posts', Arr::except($post->toArray(), ['id', 'created_at', 'updated_at']));

        $response = $this->get(route('posts.show', ['post' => $post]));
        $response->assertSeeText( $post->title );
        $response->assertSeeText( $post->content );
        $response->assertStatus(200);
    }

    public function test_store_valid_success()
    {
        $params = [
            'title' => 'Valid title : string, required and max 255 characters',
            'content' => 'Valid content : string, nullable, min 5 characters'
        ];

        $this->post(route('posts.store', $params))
             ->assertStatus(302)
             ->assertSessionHas('success');

        $this->assertEquals(session('success'), 'The blog post was successfully created');
    }

    public function test_store_invalid_failure()
    {
        // title missing, content length
        $params = [
            'title' => '',
            'content' => '1'
        ];

        $this->post(route('posts.store', $params))
            ->assertStatus(302)
            ->assertSessionHas('errors');

        $messages = session('errors')->getMessages();

        $this->assertEquals($messages['title'][0], 'The title field is required.');
        $this->assertEquals($messages['content'][0], 'The content must be at least 5 characters.');

        //title length, content length
        $params = [
            'title' => str_repeat('characters', 300),
            'content' => '1'
        ];

        $this->post(route('posts.store', $params))
            ->assertStatus(302)
            ->assertSessionHas('errors');

        $messages = session('errors')->getMessages();

        $this->assertEquals($messages['title'][0], 'The title must not be greater than 255 characters.');
        $this->assertEquals($messages['content'][0], 'The content must be at least 5 characters.');
    }

    public function test_update_valid_success()
    {
        $post = Post::create([
            'title' => 'this is a valid title',
            'content' => 'this is a valid content we will refactore this using factories'
        ]);

        $this->assertDatabaseHas('posts', Arr::except($post->toArray(), ['id', 'created_at', 'updated_at']));


    }

}

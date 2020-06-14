<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\BlogPost;
use App\Comment;

class PostTest extends TestCase
{

    use RefreshDatabase;

    public function testExample()
    {
        $this->assertTrue(true);
    }


    public function testNoBlogPostsWhenNothingInDatabase()
    {
        $response = $this->get('/posts');

        $response->assertSeeText('No blog posts yet!');
    }

    public function testSee1BlogPostWhenThereIs1WithNoComments()
    {
        //Arrange part 
        // $post = new BlogPost();
        // $post->title = 'New title';
        // $post->content = 'new content';
        // $post->save();

        $post = $this->dummyCreateBlogPost();

        //Act
        $response = $this->get('/posts');

        //Assert
        $response->assertSeeText('New title');
        $response->assertSeeText('No comments yet!');

        $this->assertDatabaseHas('blog_posts', [
            'title' => 'New title'
        ]);
    }

    public function testSee1BlogPostWithComments()
    {
        $post = $this->dummyCreateBlogPost();

        factory(Comment::class, 5)->create(['blog_post_id' => $post->id]);
        // factory('App\Comment', 5)->create(['blog_post_id' => $post->id]);

        $response = $this->get('/posts');
        $response->assertSeeText('5 comments');        
    }


    public function testStoreValid()
    {
        $params = [
            'title' => 'New title',
            'content' => 'At least 10 characters'
        ];

        $this->post('/posts', $params)
                ->assertStatus(302)
                ->assertSessionHas('status');

        $this->assertEquals(session('status'), 'Blog post was created!');        

    }



    public function testStoreFail()
    {
        $params = [
            'title' => 'x',
            'content' => 'x'
        ];
    
        $this->post('/posts', $params)
             ->assertStatus(302)
             ->assertSessionHas('errors');

          $messages = session('errors')->getMessages();

          $this->assertEquals($messages['title'][0], 'The title must be at least 3 characters.');

          $this->assertEquals($messages['content'][0], 'The content must be at least 3 characters.');

    }

    public function testUpdateValid()
    {
          //Arrange part 
        // $post = new BlogPost();
        // $post->title = 'New title';
        // $post->content = 'new content';
        // $post->save();

        $post = $this->dummyCreateBlogPost();

        //Act
        $response = $this->get('/posts');

        $this->assertDatabaseHas('blog_posts', $post->toArray());

         $params = [
            'title' => 'New title',
            'content' => 'At least 10 characters'
        ];

        $this->put("/posts/{$post->id}", $params)
                ->assertStatus(302)
                ->assertSessionHas('status');

        $this->assertEquals(session('status'), 'Blog post was updated!');  

        $this->assertDatabaseMissing('blog_posts', $post->toArray());

        $this->assertDatabaseHas('blog_posts', $params);

    }


    public function testDelete()
    {

        $post = $this->dummyCreateBlogPost();

        $this->assertDatabaseHas('blog_posts', $post->toArray());


        $this->delete("/posts/{$post->id}")
                ->assertStatus(302)
                ->assertSessionHas('status');

        $this->assertEquals(session('status'), 'Blog post was deleted!'); 

        $this->assertDatabaseMissing('blog_posts', $post->toArray());

    }

    private function dummyCreateBlogPost(): BlogPost
    {
        // $post = new BlogPost();
        // $post->title = 'New title';
        // $post->content = 'new content';
        // $post->save();

        return factory('App\BlogPost')->states('new-title')->create();

        // return $post;
    }



}




<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        if($this->command->confirm('Do you want to refresh database')){
            $this->command->call('migrate:refresh');
            $this->command->info('Database refreshed');
        }

        Cache::tags('blog-post')->flush();


        $this->call([
        	UsersTableSeeder::class,
        	BlogPostsTableSeeder::class,
			CommentsTableSeeder::class,
			TagsTableSeeder::class,
			BlogPostTagTableSeeder::class
		]);

        // DB::table('users')->insert([
	       //  'name' => 'chandan',
	       //  'email' => 'chandan@gmail.com',
	       //  'email_verified_at' => now(),
	       //  'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
	       //  'remember_token' => Str::random(10),
        // ]);

    	// $doe = factory(App\User::class)->states('john-doe')->create();
    	// $else = factory(App\User::class,20)->create();
    
    	// $users  = $else->concat([$doe]);

    	// $posts = factory(App\BlogPost::class, 50)->make()->each(function ($post) use ($users) {
    	// 	$post->user_id = $users->random()->id;
    	// 	$post->save();
    	// });

    	// $comments = factory(App\Comment::class,100)->make()->each(function ($comment) use ($posts) {
    	// 	$comment->blog_post_id = $posts->random()->id;
    	// 	$comment->save();
    	// });


    }
}




<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StorePost;
use Illuminate\Support\Facades\Auth;
use App\BlogPost;
use App\User;
use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Facades\Gate;

use Illuminate\Support\Facades\DB;


/*
 Controller Method => Policy Method
        index      => viewAny
        show       => view
        create     => create
        store      => create
        edit       =>  update
        update     => update
        destroy    => delete
*/

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')
             ->only(['create', 'store', 'edit', 'update', 'destroy']);
    }    
 

    public function index()
    {

        $mostCommented = Cache::tags(['blog-post'])->remember('blog-post-commented', 160, function(){
            return BlogPost::mostCommented()->take(5)->get();
        });

         $mostActive = Cache::remember('users-most-active', 160, function(){
            return User::withMostBlogPosts()->take(5)->get();
        });

          $mostActiveLastMonth = Cache::remember('users-most-active-last-month', 160, function(){
            return User::withMostBlogPostsLastMonth()->take(5)->get();
        });

        return view('posts.index',[
            'posts' => BlogPost::latest()->withCount('comment')->with('user')->get(),
            'mostCommented' => $mostCommented,
            'mostActive' => $mostActive,
            'mostActiveLastMonth' => $mostActiveLastMonth ,
             // 'posts' => BlogPost::withCount('comment')->orderBy('created_at', 'desc')->get()
        ]);

        // return view('posts.index',['posts' => BlogPost::all()]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $this->authorize('posts.create');
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePost $request)
    {
        $validatedData = $request->validated();

        // $validatedData['user_id'] = Auth::user()->id; //provide authorize user id
        $validatedData['user_id'] = $request->user()->id; //provide user id from authorize method

        $blogPost = BlogPost::create($validatedData);

 
        // $blogPost = new BlogPost;
        // $blogPost->title = $request->input('title');
        // $blogPost->content = $request->input('content');
        // $blogPost->save();

        $request->session()->flash('status', 'Blog post was created!');

        // return redirect('posts'); // to redirect in url
        return redirect()->route('posts.show', ['post' => $blogPost->id]);

    } 

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        // $request->session()->reflash();

        $blogPost = Cache::tags(['blog-post'])->remember("blog-post-{$id}", 60 , function () use($id) {
            return BlogPost::with('comment')->findOrFail($id);
        });

        $sessionId = session()->getId();

        $counterKey = "blog-post-{$id}-counter";  // how many users on the page
        $usersKey = "blog-post-{$id}-users"; //fetch and store informaton about users
        $users = Cache::tags(['blog-post'])->get($usersKey, []);
        $usersUpdate = [];
        $difference =0;
        $now = now();

        foreach ($users as $session => $lastVisited) {

            if($now->diffInMinutes($lastVisited) >= 1){
                $difference--;
            } else {
                $usersUpdate[$session] = $lastVisited;
            }
        }

        if(
            !array_key_exists($sessionId, $users)
            || $now->diffInMinutes($users[$sessionId]) >= 1    
        ){
                $difference++;
        }

        $usersUpdate[$sessionId] = $now;
        Cache::tags(['blog-post'])->forever($usersKey, $usersUpdate);


        if (!Cache::tags(['blog-post'])->has($counterKey)) {
            Cache::tags(['blog-post'])->forever($counterKey, 1);
        } else {
            Cache::tags(['blog-post'])->increment($counterKey, $difference);
        }

        $counter = Cache::tags(['blog-post'])->get($counterKey);

        return view('posts.show',[
            'post' => $blogPost,
            'counter'=> $counter
        ]);
        
         // return view('posts.show',['post' => BlogPost::with(['comment' => function($query){
        //     return $query->latest();
        // }])->findOrFail($id)]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post =  BlogPost::findOrFail($id);

        // if(Gate::denies('update-post', $post)){
        //     abort(403, "You can't edit this blog post!");// this is dynamically will show http response and msg
        // }
        $this->authorize($post);

        // Gate::authorize('posts.update', $post); // this is static , by deafult will show http response and msg


        return view('posts.edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePost $request, $id)
    {
        $blogPost = BlogPost::findOrFail($id);

        // if(Gate::denies('update-post',$blogPost)){
        //     abort(403, "You can't edit this blog post!");
        // }

        // Gate::authorize('update-post', $blogPost); // usig authorize helper
        // $this->authorize('posts.update', $blogPost);
        $this->authorize($blogPost);

        $validatedData = $request->validated();
        $blogPost->fill($validatedData);
        $blogPost->save();
        $request->session()->flash('status', 'Blog post was updated!');
        return redirect()->route('posts.show', ['post' => $blogPost->id]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {

        $blogPost = BlogPost::findOrFail($id);
           // $this->authorize('delete-post', $blogPost);

        // $this->authorize('posts.delete', $blogPost);
        $this->authorize($blogPost);
        // Gate::authorize('delete-post', $blogPost);
        // $blogPost->delete();

        BlogPost::destroy($id);



        $request->session()->flash('status',  'Blog post was deleted!');
        return redirect()->route('posts.index');

    }
}

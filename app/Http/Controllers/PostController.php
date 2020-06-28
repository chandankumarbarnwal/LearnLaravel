<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BlogPost;
use App\Http\Requests\StorePost;

use Illuminate\Support\Facades\Gate;

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
        return view('posts.index',['posts' => BlogPost::withCount('comment')->get()]);

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
       
        return view('posts.show',['post' => BlogPost::with('comment')->findOrFail($id)]);
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

        // BlogPost::destroy($id);



        $request->session()->flash('status',  'Blog post was deleted!');
        return redirect()->route('posts.index');

    }
}

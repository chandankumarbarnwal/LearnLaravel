<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BlogPost;
use App\Http\Requests\StorePost;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
       
        return view('posts.show',['post' => BlogPost::findOrFail($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('posts.edit', ['post' => BlogPost::findOrFail($id)]);
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
        // $blogPost = BlogPost::findOrFail($id);
        // $blogPost->delete();

        BlogPost::destroy($id);

        $request->session()->flash('status',  'Blog post was deleted!');
        return redirect()->route('posts.index');

    }
}

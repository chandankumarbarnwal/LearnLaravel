@extends('layout')

@section('title')
	home title
@endsection

@section('content')
	@forelse($posts as $post)
		<p>
			<h3><a href="{{ route('posts.show', ['post' =>$post->id]) }}">{{ $post->title }}</h3></a>
		</p>
		<a href="{{ route('posts.edit', ['post' =>$post->id]) }}" class="btn btn-primary">Edit</a>

		<form  class="fm-inline" action="{{ route('posts.destroy', ['id' =>$post->id])}}" method="POST">
			
			@csrf
			@method('DELETE')

			<input type="submit" name="" value="Delete" class="btn btn-primary">
		</form>

		@empty
			<p>data not found</p>

	@endforelse
@endsection
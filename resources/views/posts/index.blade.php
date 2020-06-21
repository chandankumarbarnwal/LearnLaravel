@extends('layout')

@section('title')
	home title
@endsection

@section('content')
	@forelse($posts as $post)
		<p>
			<h3><a href="{{ route('posts.show', ['post' =>$post->id]) }}">{{ $post->title }}</a>
			</h3>




			@if($post->comment_count)
				<p>{{ $post->comment_count }} comments</p>
			  @else
				<p>No comments yet!</p>
			@endif

		<a href="{{ route('posts.edit', ['post' =>$post->id]) }}" class="btn btn-primary">Edit</a>

		<form  class="fm-inline" action="{{ route('posts.destroy', ['id' =>$post->id])}}" method="POST">
			
			@csrf
			@method('DELETE')

			<input type="submit" name="" value="Delete" class="btn btn-primary">
		</form>
	</p>

		@empty
			<p>No blog posts yet!</p>

	@endforelse
@endsection
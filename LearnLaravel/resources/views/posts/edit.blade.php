@extends('layout')

@section('title')
	create blogpost
@endsection

@section('content')
	<form action="{{ route('posts.update',['post' => $post->id]) }}" method="POST">
		@csrf
		@method('PUT')

		@include('posts._form')
		<button type="submit">Update</button>
	</form>

@endsection






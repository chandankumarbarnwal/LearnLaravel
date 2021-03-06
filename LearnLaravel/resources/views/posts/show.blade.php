@extends('layout')

@section('title')
	home title
@endsection

@section('content')
			<h3>{{ $post->title }}</h3>
			<h3>{{ $post->content }}</h3>

			<!-- <p>Added {{$post->created_at}}</p> -->

			<p>Added {{$post->created_at->diffForHumans()}}</p>

			@if( (new carbon\carbon)->diffInMinutes($post->created_at) <5)
				<strong>{{(new carbon\carbon)->diffInMinutes($post->created_at)}} minutes as New!</strong>
			@endif


@endsection
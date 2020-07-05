@extends('layout')

@section('title')
	home title
@endsection

@section('content')
			<h1>
				{{ $post->title }}
					@badge(['show' => now()->diffInMinutes($post->created_at) <30])
						Brand new Post!
					@endbadge
			</h1>

			<h3>{{ $post->content }}</h3>

			@updated(['date' => $post->created_at, 'name' => $post->user->name])
			@endupdated

			@updated(['date' => $post->created_at])
				Updated
			@endupdated

			<p>Currently read by {{$counter}} people</p>

	<h4>Comments</h4>

	@forelse($post->comment as $cmt)
			<p>{{$cmt->content}},</p>

			@updated(['date' => $post->created_at])
			@endupdated

		@empty
			<p>No comments yet!</p>
	@endforelse

@endsection

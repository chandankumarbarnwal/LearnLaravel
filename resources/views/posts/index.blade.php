@extends('layout')

@section('title')
	home title
@endsection

@section('content')
<div class="row">
	<div class="col-8">

	@forelse($posts as $post)
		<p>
			<h3>
				@if($post->trashed())
					<del>
				@endif
				
					<a class="{{ $post->trashed() ? 'text-muted':''}}" href="{{ route('posts.show', ['post' =>$post->id]) }}">{{ $post->title }}</a>
				@if($post->trashed())
				</del>
				@endif	
			</h3>
			

			@updated(['date' => $post->created_at, 'name' => $post->user->name])

			@endupdated


			@if($post->comment_count)
				<p>{{ $post->comment_count }} comments</p>
			  @else
				<p>No comments yet!</p>
			@endif

			@can('update', $post)	
				<a href="{{ route('posts.edit', ['post' =>$post->id]) }}" class="btn btn-primary">Edit</a>
			@endcan	

	<!-- 	@cannot('delete', $post)
			<span>You cannot delete</span>
		@endcannot
 -->
 		@if(!$post->trashed())
			@can('delete', $post)
				<form  class="fm-inline" action="{{ route('posts.destroy', ['id' =>$post->id])}}" method="POST">
					
					@csrf
					@method('DELETE')

					<input type="submit" name="" value="Delete" class="btn btn-primary">
				</form>
			@endcan	
		@endif	

	    </p>

		@empty
			<p>No blog posts yet!</p>

	@endforelse
	</div>

	<div class="col-4">
		<div class="container">
			<div class="row">

				@card(['title' => 'Most Commented'])
					@slot('subtitle')
						What people are currently taking about
					@endslot
					@slot('items')
						@foreach($mostCommented as $post)
						    <li class="list-group-item">
						    	<a href="{{ route('posts.show', ['id' => $post->id]) }}" >{{$post->title}}
						    	</a>
						    </li>
					  	@endforeach 
					@endslot

				@endcard
				

			<!-- 	<div class="card" style="width:100%;">

					<div class="card-body">
						<h5 class="card-title">Most Commented</h5>
						<h6 class="card-subtitle mb-2 text-muted">
							What people are currently taking about
						</h6>
					</div>	

					<ul class="list-group list-group-flush">
					 @foreach($mostCommented as $post)
					    <li class="list-group-item">
					    	<a href="{{ route('posts.show', ['id' => $post->id]) }}" >{{$post->title}}
					    	</a>
					    </li>
				  	 @endforeach 
				    </ul>
			    </div> -->
			</div>  


			<div class="row mt-4">

			    @card(['title' => 'Most Active'])
			    	@slot('subtitle')
			  			Writers with most posts written
			  		@endslot	
			    	@slot('items', collect($mostActive)->pluck('name'))

			    @endcard

			</div>  


			<div class="row mt-4">
				<div class="card" style="width:100%;">

			    @card(['title' => 'Most Active Last Month'])
			    	@slot('subtitle')
			    		Users with most posts written in the month
			  		@endslot	
			    	@slot('items', collect($mostActiveLastMonth)->pluck('name'))

			    @endcard

			</div>  

		</div>    
	</div>	
</div>
@endsection



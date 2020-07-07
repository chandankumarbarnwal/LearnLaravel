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
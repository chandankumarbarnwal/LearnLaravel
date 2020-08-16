<!DOCTYPE html>
<html>
<head>
	<title>@yield('title')</title>

<link rel="stylesheet" type="text/css" href="{{ mix('css/app.css') }}">

</head>
<body>

<!-- <ul>
	<li><a href="{{ route('home') }}">home</a></li>
	<li><a href=" {{ route('contact')}}">contact</a></li>
	<li><a href=" {{ route('posts.index')}}">BlogPost</a></li>
	<li><a href=" {{ route('posts.create')}}">Add BlogPost</a></li>

</ul> -->


<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
    <h5 class="my-0 mr-md-auto font-weight-normal">Laravel Blog</h5>
    <nav class="my-2 my-md-0 mr-md-3">
        <a class="p-2 text-dark" href="{{ route('home') }}">Home</a>
        <a class="p-2 text-dark" href="{{ route('contact') }}">Contact</a>
        <a class="p-2 text-dark" href="{{ route('posts.index') }}">Blog Posts</a>
        <a class="p-2 text-dark" href="{{ route('posts.create') }}">Add </a>

        @guest
        	@if(Route::has('register'))
        		<a class="p-2 text-dark" href="{{ route('register') }}">Register</a>
        	@endif
        	<a class="p-2 text-dark" href="{{ route('login') }}">Login</a>

        @else

        	<a class="p-2 text-dark" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout ({{Auth::user()->name }})</a>

        	<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
       			@csrf 		
        	</form>

        @endguest

    </nav>
</div>

<div class="container" >

		@if(session()->has('status'))
			<p style="color:green">
				{{session()->get('status')}}
			</p>
		@endif

		@yield('content')
</div>

	<script type="text/javascript" src="{{  mix('js/app.js') }}"></script>

</body>
</html>













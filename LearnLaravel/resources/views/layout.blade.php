<!DOCTYPE html>
<html>
<head>
	<title>@yield('title')</title>
</head>
<body>

<ul>
	<li><a href="{{ route('home') }}">home</a></li>
	<li><a href=" {{ route('contact')}}">contact</a></li>
	<li><a href=" {{ route('posts.index')}}">BlogPost</a></li>
	<li><a href=" {{ route('posts.create')}}">Add BlogPost</a></li>

</ul>

@if(session()->has('status'))
	<p style="color:green">
		{{session()->get('status')}}
	</p>
@endif

	@yield('content')

</body>
</html>
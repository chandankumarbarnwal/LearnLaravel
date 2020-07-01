@if(!isset($show)||$show)

	{{!isset($show)}}
	<span class="badge badge-{{$type ?? 'success'}}">
			{{$slot}}
	</span>
@endif
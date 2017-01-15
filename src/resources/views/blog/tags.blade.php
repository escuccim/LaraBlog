@unless ($blog->tags->isEmpty())
	<p><small><i><strong>{{ trans('larablog::blog.labels') }}:</strong>
	@foreach($blog->tags as $tag)
		<a href="{{ url('/blog/labels/' . $tag->name) }}">{{ $tag->name }}@unless($loop->last),
		@endunless</a>
	@endforeach</i></small>
@endif
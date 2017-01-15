@extends('layouts.app')

@section('header')
<link rel="alternate" type="application/rss+xml" title="RSS Feed" href="{{ url('feed') }}" />
@endsection

@section('content')
<div class="container">
	@include('flash::message')
	<div class="row">
		<div class="col-md-10">
			@if(Request::is( 'blog/labels*'))
				<div class="alert alert-warning alert-important text-center">
				{{ trans('larablog::blog.showinglabel') }} <strong>{{ $name }}</strong>.
					<a href="{{ url('/blog')}}">{{ trans('larablog::blog.showallposts') }}</a>
				</div>
			@endif

			@foreach($blogs as $blog)
				<article>
					<div class="panel {{ $blog->getBlogStatus()  }}">
						<div class="panel-heading">
							<h3>
								<a href="{{ url('/blog/' . $blog->slug) }}">{{ $blog->title }}</a>
							</h3>
					<strong>{{ strftime('%A %d %B %Y', strtotime($blog->published_at)) }}	</strong>
						</div>
						<div class="panel-body">
							{!! $blog->body !!}
							@include('escuccim::blog.tags')<br>
							<a href="{{ url('/blog/' . $blog->slug) }}">
							@if($blog->comments->count())
						<small>{{ $blog->comments->count() }} {{ trans('larablog::blog.noofcomments') }}</small>
							@else
						<small>{{ trans('larablog::blog.nocomments') }}</small>
							@endif
							</a>
						</div>
					</div>
				</article>
			@endforeach
		</div>

		<div class="col-md-2">
			@if(!Auth::guest())
				@if(Auth::user()->type)
				<div class="text-right">
					<a href="/blog/create" class="btn btn-primary vcenter">Add Blog Post</a>
				</div>
				@endif
			@endif
			@include('escuccim::blog.archives')
		</div>
	</div>

	<div class="row">
		<div class="col-md-10 text-center">
			{{ $blogs->links() }}
		</div>
	</div>
</div>
@endsection
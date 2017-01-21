@extends('layouts.app')

@section('content')
<div class="container">
	@if(config('blog.show_flash_messages'))
		@include('flash::message')
	@endif
	<div class="row">
		<div class="col-md-10">
			<article>
				<div class="panel {{ $blog->getBlogStatus() }}">
					<div class="panel-heading">
			
				<i>{{ strftime('%A %d %B %Y', strtotime($blog->published_at)) }} {{ trans('larablog::blog.at') }} {{ strftime('%A %d %B %Y', strtotime($blog->published_at))  }}</i>
						<h2>{{ $blog->title }}</h2>
				{{ trans('larablog::blog.by') }} {{ $blog->user->name }}
					</div>

					<div class="panel-body">
						{!! $blog->body !!}
						@include('escuccim::blog.tags')
						@if(config('blog.is_user_admin')())
							<div class="row">
								<div align="center">
									<div class="btn-group">
										{!! Form::open(['method' => 'delete', 'class' => 'form-horizontal', 'url' => '/blog/' . $blog->id]) !!}
										<a href="{{ url('/blog/' . $blog->id . '/edit') }}" class="btn btn-primary">Edit Blog</a>
										<button type="submit" id="deleteBlog" class="btn btn-default">Delete Blog</button>
										{!! Form::close() !!}
									</div>
								</div>
							</div>
						@endif
					</div>
				</div>
			</article>
			<hr>
			@include('escuccim::blog.comments')
		</div>
		<div class="col-md-2">
			@include('escuccim::blog.archives')
		</div>
	</div>
</div>
<script>
    new Vue({
        el: '#comment',
        data: {
            comment: '',
        }
    });
</script>
@endsection

@extends('layouts.app')

@section('header')
	@if(config('blog.use_rich_card'))
		@include('escuccim::blog.richCard')
	@endif
@endsection

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
			
				<i>{{ strftime('%A %d %B %Y', strtotime($blog->published_at)) }} {{ trans('larablog::blog.at') }} {{ strftime('%H:%M', strtotime($blog->updated_at))  }}</i>
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
										<form action="/blog/{{$blog->id}}" class="form-horizontal" method="post">
											{{ csrf_field() }}
											<input name="_method" type="hidden" value="DELETE">
										<a href="{{ url('/blog/' . $blog->id . '/edit') }}" class="btn btn-primary">{{ trans('larablog::blog.editpost') }}</a>
										<button type="submit" id="deleteBlog" class="btn btn-default">{{ trans('larablog::blog.deletepost') }}</button>
										</form>
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

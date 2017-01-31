<div id="comment">
<h4>{{ trans('larablog::blog.comments') }}</h4>
@if(Auth::guest())
	<p><a href="/login">{{ trans('larablog::blog.login') }}</a> {{ trans('larablog::blog.or') }} <a href="/register">{{ trans('larablog::blog.register') }}</a> {{ trans('larablog::blog.toleaveacomment') }}.
	<p><div class="g-signin2" data-onsuccess="onSignIn" data-longtitle="true" data-width="200"></div><br />
@else
	<div class="panel panel-default">
		<div class="panel-heading">
			<a data-toggle="collapse" href="#comment-collapse" id="leave-comment"> 
				{{ trans('larablog::blog.leaveacomment') }}
			</a>	
		</div>
		<div id="comment-collapse" class="panel-collapse collapse">
			@include('escuccim::comments._form')
		</div>	
	</div>
@endif
</div>

@include('escuccim::comments.index')


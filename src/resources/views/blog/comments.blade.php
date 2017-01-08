<div id="comment">
<h4>Comments</h4>
@if(Auth::guest())
	<p><a href="/login">Login</a> or <a href="/register">Register</a> to comment.
	<p><div class="g-signin2" data-onsuccess="onSignIn" data-longtitle="true" data-width="200"></div><br />
@else
	<div class="panel panel-default">
		<div class="panel-heading">
			<a data-toggle="collapse" href="#comment-collapse"> 
				Leave a Comment
			</a>	
		</div>
		<div id="comment-collapse" class="panel-collapse collapse">
			<div class="panel-body">
				{{ Form::open(['method' => 'post', 'url' => '/blog/comment/add']) }}
					<input type="hidden" name="blog_id" value="{{ $blog->id }}">
					<input type="hidden" name="slug" value="{{ $blog->slug }}">
					<div class="form-group">
						<textarea required="required" placeholder="Enter comment here" name="body" class="form-control" v-model="comment"></textarea>
					</div>
					<input type="submit" name="post_comment" class="btn btn-primary" value="Post Comment" :disabled="!comment">
				{{ Form::close() }}
			</div>
		</div>	
	</div>
@endif
</div>

@if($comments)
	<ul style="list-style: none; padding:0;">
		@foreach($comments as $comment)
			<li class="panel-body" style="padding: 0px;">
				<div class="list-group">
					<div class="list-group-item">
						@if($comment->author->image)
							<img src="{{ $comment->author->image }}" align="left" style="padding-right: 5px; max-height: 40px" alt="{{ $comment->author->name }}">
						@endif
						<strong>{{ $comment->author->name }}</strong>
						<p> {{ $comment->created_at->format('M d, Y \a\t h:i a') }}
					</div>
					<div class="list-group-item">
						<p>{{ $comment->body }}
					</div>
				</div>
			</li>
		@endforeach
	</ul>	
@endif


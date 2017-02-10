<article class="row">
    <div class="col-md-12">
        <div class="panel-body" style="padding: 0px;">
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
                @if(Auth::check())
                    @if(Auth::user()->id == $comment->author->id)
                        <form action="/blog/comment/delete/{{ $comment->id }}" method="post" class="form-horizontal" onSubmit="return confirm('Are you sure you want to delete this comment?');">
                    @endif

                    <div class="list-group-item">
                    <a href="#comment-parent-{{$comment->id}}" data-toggle="collapse" class="btn btn-primary btn-sm">{!! trans('larablog::blog.reply') !!}</a>
                    &nbsp; &nbsp; &nbsp;
                    @if(Auth::user()->id == $comment->author->id)
                            <input name="_method" type="hidden" value="DELETE">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-default btn-sm">{!! trans('larablog::blog.delete') !!}</button>
                    @endif
                    </div>

                    @if(Auth::user()->id == $comment->author->id)
                        </form>
                    @endif
                @endif
            </div>
        </div>
    </div>
</article>
@if(Auth::check())
    <div class="panel panel-default collapse" id="comment-parent-{{$comment->id}}">
        @include('escuccim::comments._form')
    </div>
@endif
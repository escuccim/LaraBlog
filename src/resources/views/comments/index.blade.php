@if($comments)
    @foreach($comments as $comment)
        @include('escuccim::comments.show')
        @if(count($comment->replies()))
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-11">
                    @include('escuccim::comments.index', ['comments' => $comment->replies()])
                </div>
            </div>
        @endif
    @endforeach
@endif
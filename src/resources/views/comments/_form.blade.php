<div class="panel-body">
    <form action="/blog/comment/add" method="post">
        {{ csrf_field() }}
    <input type="hidden" name="blog_id" value="{{ $blog->id }}">
    <input type="hidden" name="slug" value="{{ $blog->slug }}">
    @if(isset($comment->id))
        <input type="hidden" name="parent_comment_id" value="{{ $comment->id }}">
    @endif
    <div class="form-group">
        <textarea required="required" placeholder="{{ trans('larablog::blog.entercomment') }}" name="body" class="form-control" v-model="comment"></textarea>
    </div>
    <input type="submit" name="post_comment" class="btn btn-primary" value="{{ trans('larablog::blog.postcomment') }}" :disabled="!comment">
    </form>
</div>
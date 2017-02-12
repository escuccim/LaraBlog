<div id="app">
<div class="form-group">
	<label for="title" class="control-label col-md-1">{{ trans('larablog::blog.title') }}:</label>
	<div class="col-md-10">
		<input class="form-control" name="title" type="text" id="title" value="{{ $blog->title }}">
	</div>
</div>

<div class="form-group">
	<label for="slug" class="control-label col-md-1">{{ trans('larablog::blog.slug') }}:</label>
	<div class="col-md-10">
		<input class="form-control" name="slug" type="text" id="slug" value="{{ $blog->slug }}">
	</div>
</div>

@if(config('blog.use_rich_card'))
	<div class="form-group">
		<label for="image" class="control-label col-md-1">{{ trans('larablog::blog.image') }}:</label>
		<div class="col-md-10">
			<input class="form-control" name="image" type="text" id="image" value="{{ $blog->image }}">
		</div>
	</div>
@endif

<div class="form-group">
	<label for="body" class="control-label col-md-1">{{ trans('larablog::blog.body') }}:</label>
	<div class="col-md-10">
		<textarea class="form-control" id="body" name="body" cols="50" rows="10">{{ $blog->body }}</textarea>
	</div>
</div>

<div class="form-group">
	<label for="tags" class="control-label col-md-1">{{ trans('larablog::blog.tags') }}:</label>
	<div class="col-md-10">
		<select id="tags" class="form-control" multiple="multiple" name="tags[]">
			@foreach($tags as $key => $value)
				<option value="{{$key}}" @if(count($tagArray))@foreach($tagArray as $tag) {{ $tag == $key ? ' selected' : '' }}@endforeach @endif>{{$value}}</option>
			@endforeach
		</select>
	</div>
</div>

<div class="form-group">
	<label for="published_at" class="control-label col-md-1">{{ trans('larablog::blog.publish_on') }}:</label>
	<div class="col-md-10">
		<input class="form-control" name="published_at" type="date" value="{{$blog->published_at->format('Y-m-d')}}">
	</div>
</div>

<div class="form-group">
	<label for="published" class="control-label col-md-1">{{ trans('larablog::blog.published') }}:</label>
	<div class="col-md-10">
		<input class="radio-inline" name="published" type="radio" value="1" @if($blog->published) checked @endif> {{ trans('larablog::blog.yes') }}
		<input class="radio-inline" name="published" type="radio" value="0" @if(!$blog->published) checked @endif> {{ trans('larablog::blog.no') }}
	</div>
</div>

<div class="form-group text-center">
	<button type="submit" class="btn btn-primary">{{ $submitButtonText }}</button>
</div>
</div>

<script>
    $('#tags').select2({
        placeholder: 'Choose a tag:',
        allowClear: true,
        tags: true,
    });
    CKEDITOR.replace( 'body' );
</script>

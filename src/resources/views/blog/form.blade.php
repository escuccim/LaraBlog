<div id="app">
<div class="form-group">
	{!! Form::label('title', trans('larablog::blog.title') . ':', ['class' => 'control-label col-md-1']) !!}
	<div class="col-md-10">
		{!! Form::text('title', null, ['class' => 'form-control']) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('slug', trans('larablog::blog.slug') . ':', ['class' => 'control-label col-md-1']) !!}
	<div class="col-md-10">
		{!! Form::text('slug', null, ['class' => 'form-control']) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('body', trans('larablog::blog.body') . ':', ['class' => 'control-label col-md-1']) !!}
	<div class="col-md-10">
		{!! Form::textarea('body', null, ['class' => 'form-control', 'id' => 'body']) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('tags', trans('larablog::blog.tags') . ':', ['class' => 'control-label col-md-1']) !!}
	<div class="col-md-10">
		{!! Form::select('tags[]', $tags, $tagArray, ['id' => 'tags', 'class' => 'form-control', 'multiple' => 'multiple']) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('published_at', trans('larablog::blog.publish_on') . ':', ['class' => 'control-label col-md-1']) !!}
	<div class="col-md-10">
		{!! Form::input('date', 'published_at', $blog->published_at->format('Y-m-d'), ['class' => 'form-control']) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('published', trans('larablog::blog.published').':', ['class' => 'control-label col-md-1']) !!}
	<div class="col-md-10">
		{!! Form::radio('published', 1, null, ['class' => 'radio-inline']) !!} {{ trans('larablog::blog.yes') }}
		{!! Form::radio('published', 0, null, ['class' => 'radio-inline']) !!} {{ trans('larablog::blog.no') }}
	</div>
</div>

<div class="form-group text-center">
	<button type="submit" class="btn btn-primary">{{ $submitButtonText }}</button>
</div>
</div>

<script>
    CKEDITOR.replace( 'body' );
	$('#tags').select2({
		placeholder: 'Choose a tag:',
		allowClear: true,
		tags: true,
	});

</script>

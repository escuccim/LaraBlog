<div id="app">
<div class="form-group">
	{!! Form::label('title', trans('larablog::blog.title') . ':', ['class' => 'control-label col-md-1']) !!}
	<div class="col-md-10">
		{!! Form::text('title', null, ['class' => 'form-control', 'v-model' => 'title']) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('slug', trans('larablog::blog.slug') . ':', ['class' => 'control-label col-md-1']) !!}
	<div class="col-md-10">
		{!! Form::text('slug', null, ['class' => 'form-control', 'v-model' => 'slug']) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('body', trans('larablog::blog.body') . ':', ['class' => 'control-label col-md-1']) !!}
	<div class="col-md-10">
		{!! Form::textarea('body', null, ['class' => 'form-control', 'id' => 'body', 'v-model' => 'body']) !!}
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
		{!! Form::input('date', 'published_at', $blog->published_at->format('Y-m-d'), ['class' => 'form-control', 'size' => 100, 'v-model' => 'published_at']) !!}
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
	<button type="submit" class="btn btn-primary" :disabled="!(title && slug && published_at)">{{ $submitButtonText }}</button>
</div>
</div>

<script>
	new Vue({
		el: '#app',
		data: {
			title: '',
			slug: '',
			body: '',
			publish_at: '',
		},
	});
	$('#tags').select2({
		placeholder: 'Choose a tag:',
		allowClear: true,
		tags: true,
	});
    CKEDITOR.replace( 'body' );
</script>

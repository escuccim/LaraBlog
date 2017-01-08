@extends('layouts.app')

@section('header')
	<script src="/js/app.js"></script>
	<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
	<script>tinymce.init({ selector:'#body' });</script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
@endsection

@section('content')
<div class="container">
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h2>Write a New Blog Post</h2>
			</div>
			<div class="panel-body">
				@include('escuccim::errors.list')
		
				{!! Form::model($blog, ['url' => 'blog', 'class' => 'form-horizontal']) !!}
					@include('escuccim::blog.form', ['submitButtonText' => 'Add Blog Post'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
</div>
@endsection
@extends('layouts.app')

@section('header')
	<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
	<script>tinymce.init({ selector:'#body' });</script>
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h2>Edit: {!! $blog->title !!}</h2>
			</div>
			<div class="panel-body">
				@include('errors.list')

				{!! Form::model($blog, ['method' => 'patch', 'class' => 'form-horizontal', 'url' => '/blog/' . $blog->id]) !!}
					@include('escuccim::blog.form', ['submitButtonText' => 'Update Blog'])
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
	
@endsection

@section('footer')

@endsection
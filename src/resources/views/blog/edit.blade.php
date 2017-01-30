@extends('layouts.app')

@push('scripts')
	<script
			src="https://code.jquery.com/jquery-3.1.1.min.js"
			integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
			crossorigin="anonymous"></script>
	
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

	<script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
@endpush

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h2>{{ trans('larablog::blog.edit') }}: {!! $blog->title !!}</h2>
				</div>
				<div class="panel-body">
					@include('escuccim::errors.list')
					{!! Form::model($blog, ['method' => 'patch', 'class' => 'form-horizontal', 'url' => '/blog/' . $blog->id]) !!}
						@include('escuccim::blog.form', ['submitButtonText' => trans('larablog::blog.update')])
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

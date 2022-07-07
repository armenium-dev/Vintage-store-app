@extends('layouts.app',['title' => 'Edit Size'])
@section('content')
<div class="card-body">
	<div class="row">
		<div class="col-md-8 text-left">
			<h4>{{ucfirst(str_replace('_', ' ', $settings->name))}} Option:</h4>
		</div>
		<div class="col-md-4 text-right">
			<h4>Edit mode</h4>
		</div>
	</div>
	<hr>
	@if (session('status'))
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		{{ session('status') }}
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
		</button>
	</div>
	@endif
	@if ($errors->any())
	<div class="alert alert-danger">
		<ul>
			@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
	@endif
	<form action="{{ route('settings.update',$settings->id) }}" method="POST">
		@csrf
		<input name="_method" type="hidden" value="PATCH">
		<div class="form-group">
			<label for="name">Name:</label>
			<input type="text" class="form-control" name="name" placeholder="Option name" value="{{$settings->name}}">
		</div>
		<div class="form-group">
			<label for="name">Value:</label>
			<input type="text" class="form-control" name="value" placeholder="Option value" value="{{$settings->value}}">
		</div>
		<button class="btn btn-primary" type="submit">Submit</button>
		<a href="{{route('settings.index')}}" class="btn btn-secondary">Cancel</a>
	</form>
</div>
@endsection
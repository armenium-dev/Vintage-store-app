@extends('layouts.app',['title' => 'Create Size'])
@section('content')
<div class="card-body">
	<div class="row">
		<div class="col-md-6 text-left">
			<h4>Add Setting Option:</h4>
		</div>
	</div>
	<hr>
	<x-alert type="error" :message="session('status')"/>
	@if ($errors->any())
	<div class="alert alert-danger">
		<ul>
			@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
	@endif
	<form action="{{ route('settings.store') }}" method="POST">
		{{ csrf_field() }}
		<div class="form-group">
			<label for="name">Name:</label>
			<input type="text" class="form-control" name="name" placeholder="Option name">
		</div>
		<div class="form-group">
			<label for="name">Value:</label>
			<input type="text" class="form-control" name="value" placeholder="Option value">
		</div>
		<button class="btn btn-primary" type="submit">Submit</button>
	</form>
</div>
@endsection
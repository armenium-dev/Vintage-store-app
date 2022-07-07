@extends('layouts.app',['title' => 'Manage Settings'])
@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css"/>
{{--<link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}"/>--}}
@endsection
@section('content')
<div class="card-body">
	<div class="row">
		<div class="col-md-6 text-left"><h4>Manage Settings:</h4></div>
		<div class="col-md-6 text-right"><a href="{{ route('settings.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i>Add New</a></div>
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
	<table class="table table-striped settings" id="table">
		<thead class="thead-dark text-center">
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Value</th>
				<th>Updated</th>
				<th data-sortable="false">Edit</th>
				<th data-sortable="false">View</th>
				<th data-sortable="false">Delete</th>
			</tr>
		</thead>
		<tbody>
		@forelse($settings as $setting)
			<tr>
				<td>{{ $loop->iteration }}</td>
				<td>{{ $setting->name }}</td>
				<td>{{ $setting->value }}</td>
				<td>{{ $setting->updated_at->format('M d, Y') }}</td>
				<td><a href="{{route('settings.edit', $setting->id)}}" class="btn btn-secondary"><i class="fa fa-edit"></i></a></td>
				<td><a href="{{route('settings.show', $setting->id)}}" class="btn btn-primary"><i class="fa fa-eye"></i></a></td>
				<td>
					<button class="btn btn-danger" data-toggle="modal" data-target="#myModal_{{$setting->id}}" title="Delete"><i class="fa fa-trash"></i></button>
					<div id="myModal_{{$setting->id}}" class="modal fade" role="dialog">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header bg-dark text-center">
									<h4 class="modal-title text-light">You want delete this Option?</h4>
									<button type="button" class="close text-light" data-dismiss="modal">&times;</button>
								</div>
								<div class="modal-body">
									<form action="{{ route('settings.destroy', $setting->id) }}" method="POST">
										@csrf
										@method('DELETE')
										<div class="row">
											<div class="col-md-6 text-center"><button type="submit" class="btn btn-danger">Delete</button></div>
											<div class="col-md-6 text-center"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>
										</div>
									</form>
								</div>
								<div class="modal-footer">
								</div>
							</div>
						</div>
					</div>
				</td>
			</tr>
		@empty
			<tr>
				<td colspan="4">
					Empty
				</td>
			</tr>
		@endforelse
		</tbody>
	</table>
</div>
<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-dark text-center">
				<h4 class="modal-title text-light">You want delete this Design?</h4>
				<button type="button" class="close text-light" data-dismiss="modal">&times;</button>
			</div>
			<form action="#" method="POST">
				<div class="modal-body">
					<h5 id="reference_id" class="text-center mt-3 mb-4">Reference # <span></span></h5>
					@csrf
					@method('DELETE')
				</div>
				<div class="modal-footer">
					<div class="row">
						<div class="col-md-6 text-center"><button type="submit" class="btn btn-danger">Delete</button></div>
						<div class="col-md-6 text-center"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection
@section('scripts')
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function($){
		$('#table').DataTable({
			"order": [[0, "desc"]],
			"pageLength": 200,
			"lengthMenu": [[10, 25, 50, 75, 100, 200, -1], [10, 25, 50, 75, 100, 200, "All"]],
			"processing": true,
			"serverSide": true,
			"ajax": "/settings/parts"
		});

		$(document).on('click', '.btn-remove', function(e){
			var action = $(this).data('action'),
				$target = $($(this).data('target')),
				reference_id = $(this).data('reference_id');
			$target
				.find('form').attr('action', action)
				.end()
				.find('#reference_id').find('span').text(reference_id);

		});
	});
</script>
@endsection

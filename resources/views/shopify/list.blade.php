@extends('layouts.app',['title' => 'Shopify Products'])
@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css"/>
{{--<link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}"/>--}}
@endsection
@section('content')
<div class="card-body">
	<div class="row">
		<div class="col-md-12 text-center">
			<h4>Sync Jersey Builder Products</h4>
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
	<div class="row">
		<table class="table table-striped text-center" id="table">
			<thead class="thead-dark">
				<tr>
					<th>#</th>
					<th>Product ID</th>
					<th>Product Name</th>
					<th>Action</th>
					<th>Shopify Product Name</th>
					<th>Shopify Product ID</th>
				</tr>
			</thead>
			<tbody>
				@forelse($SyncProducts as $k => $product)
				<tr id="product_{{$product['id']}}">
					<th>{{($k+1)}}</th>
					<td>{{$product['id']}}</td>
					<td>{{$product['name']}}</td>
					<td>
						<button class="btn btn-warning syncProduct" data-toggle="modal" data-target="#myModal_{{$product['id']}}" title="Sync product name"><i class="fa fa-arrow-left"></i></button>
						<div id="myModal_{{$product['id']}}" class="modal fade" role="dialog">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header bg-dark text-center">
										<h4 class="modal-title text-light">You want sync this Product name?</h4>
										<button type="button" class="close text-light" data-dismiss="modal">&times;</button>
									</div>
									<div class="modal-body">
										<form action="{{ route('builder.ajaxupdatefields') }}" method="POST" class="shopifysync-form" data-modal="#myModal_{{$product['id']}}">
											@csrf
											<input type="hidden" name="id" value="{{$product['id']}}">
											<input type="hidden" name="name" value="{{$product['shopify_name']}}">
											<div class="row">
												<div class="col-md-6 text-center"><button type="submit" class="btn btn-danger">Yes</button></div>
												<div class="col-md-6 text-center"><button type="button" class="btn btn-default" data-dismiss="modal">No</button></div>
											</div>
										</form>
									</div>
									<div class="modal-footer">

									</div>
								</div>
							</div>
						</div>
					</td>
					<td>{{$product['shopify_name']}}</td>
					<td>{{$product['shopify_id']}}</td>
				</tr>
				@empty
				<tr>
					<td colspan="6">Nothing to show</td>
				</tr>
				@endforelse
			</tbody>
		</table>
		{{-- $Products->links() --}}
	</div>
</div>
@endsection
@section('scripts')
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
	function syncProduct(el){
		var modal_id = $(el).data('modal');
		$(modal_id).modal('hide');
		$.ajax({
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			url: "{{route('builder.ajaxupdatefields')}}",
			type: 'POST',
			data: $(el).serializeArray(),
			success: function(response){
				console.log(response);
				var td_class = (~~response.error == 0) ? 'alert-success' : 'alert-danger';
				$('tr#product_'+response.id).find('td').remove().end().html('<td colspan="6" class="text-center '+td_class+'">'+response.message+'</td>');
			}
		});
	}

	jQuery(document).ready(function($){
		$('#table').DataTable({
			"order": [[ 1, "asc" ]],
			"pageLength": 10,
			"lengthMenu": [[10, 25, 50, 75, 100, -1], [10, 25, 50, 75, 100, "All"]],
		});

		$('.shopifysync-form').on('submit', function(e){
			e.preventDefault();
			syncProduct($(this));
			return false;
		});
	});
</script>
@endsection
<x-app-layout>
	<x-slot name="header">
		<div class="">
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Mystery Box Orders') }}</h2>
		</div>
		<x-alert type="error" :message="session('status')"/>
	</x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white shadow-sm sm:rounded-lg">
				<div class="p-6 bg-white border-b border-gray-200">

					<table class="table-auto border-collapse border border-slate-400">
                        <thead>
                            <tr>
                                <th>Shop</th>
                                <th>Order ID</th>
                                <th>Payment Status</th>
                                <th>Fulfillment Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->getShopName() }}</td>
                                <td>{{ $order->order_id }}</td>
                                <td>{{ $order->payment_status }}</td>
                                <td>{{ $order->fulfillment_status }}</td>
                                <td>{{ $order->data['updated_at'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
					</table>

				</div>
			</div>
		</div>
	</div>


</x-app-layout>

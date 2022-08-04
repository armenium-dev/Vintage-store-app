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
				<div class="p-6 bg-white border-b border-gray-200 rounded-md">

                        <table class="table-auto w-full">
                            <thead>
                                <tr class="text-slate-400 dark:text-slate-200 text-left cursor-default">
                                    <th class="border-b dark:border-slate-600 font-medium p-3 hidden">Shop</th>
                                    <th class="border-b dark:border-slate-600 font-medium p-3">Order ID</th>
                                    <th class="border-b dark:border-slate-600 font-medium p-3">Payment Status</th>
                                    <th class="border-b dark:border-slate-600 font-medium p-3">Fulfillment Status</th>
                                    <th class="border-b dark:border-slate-600 font-medium p-3">Date</th>
                                    <th class="border-b dark:border-slate-600 font-medium p-3">Product</th>
                                    <th class="border-b dark:border-slate-600 font-medium p-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr id="js_orderitem_{{$order->id}}" class="text-slate-500 dark:text-slate-400 hover:bg-indigo-100 cursor-default">
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-3 hidden">{{ $order->getShopName() }}</td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-3">{{ $order->order_id }}</td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-3">{{ $order->payment_status }}</td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-3">{{ $order->fulfillment_status }}</td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-3">{{ $order->updated_at->format('Y-m-d H:i') }}</td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-3">{!! $order->productName() !!}</td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-3">
                                        <div class="flex flex-nowrap">
                                            <a href="{!! route('mysteryBoxCollect', [$order->id]) !!}"
                                                class="hover:bg-indigo-600 bg-indigo-500 focus:outline-none focus:ring focus:ring-indigo-200 transition ease-in-out duration-150 flex items-center rounded-md text-white text-sm font-medium pl-2 pr-3 py-1 mr-2 shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                                                </svg>
                                                {{ __('Collect') }}
                                            </a>
                                            <button type="button" data-trigger="js_action_click" data-action="" data-id="{{$order->id}}" data-parent="#js_orderitem_{{$order->id}}"
                                                    class="hover:bg-indigo-600 bg-indigo-500 focus:outline-none focus:ring focus:ring-indigo-200 transition ease-in-out duration-150 flex items-center rounded-md text-white text-sm font-medium p-1 shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="group-disabled:hidden h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                                <svg class="hidden group-disabled:animate-spin group-disabled:block h-5 w-5 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

				</div>
			</div>
		</div>
	</div>


</x-app-layout>

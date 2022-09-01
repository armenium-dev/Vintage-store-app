<x-app-layout>
	<x-slot name="header">
		<div class="flex justify-between">
            <div>
			    <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Mystery Box Orders') }}</h2>
                <small class="text-lime-500">{{__('Filtered count:')}} {{$total}}</small>
            </div>
            <div>
                <div class="mt-1 flex rounded-md shadow-sm">
                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">{{ __('Filters') }}</span>
                    <select data-trigger="js_action_change" data-action="orders_filter" name="fulfilled"
                            class="js_orders_filter border border-gray-300 border-r-0 text-gray-900 text-sm rounded-none focus:ring-0 focus:border-gray-300 block p-2.5 pr-7">
                        <option value="0" @if($filter['fulfilled'] == 0) selected @endif>{{__('Unfulfilled')}}</option>
                        <option value="1" @if($filter['fulfilled'] == 1) selected @endif>{{__('Fulfilled')}}</option>
                    </select>
                    <select data-trigger="js_action_change" data-action="orders_filter" name="finished"
                            class="js_orders_filter border border-gray-300 border-r-0 text-gray-900 text-sm rounded-none focus:ring-0 focus:border-gray-300 block p-2.5 pr-7">
                        <option value="0" @if($filter['finished'] == 0) selected @endif>{{__('Unfinished')}}</option>
                        <option value="1" @if($filter['finished'] == 1) selected @endif>{{__('Finished')}}</option>
                    </select>
                    <select data-trigger="js_action_change" data-action="orders_filter" name="ordering"
                            class="js_orders_filter border border-gray-300 text-gray-900 text-sm rounded-none rounded-r-md focus:ring-0 focus:border-gray-300 block p-2.5 pr-7">
                        <option value="id-asc" @if($filter['ordering'] == 'id-asc') selected @endif>{{__('Order by ID')}} &downarrow;</option>
                        <option value="id-desc" @if($filter['ordering'] == 'id-desc') selected @endif>{{__('Order by ID')}} &uparrow;</option>
                        <option value="date-asc" @if($filter['ordering'] == 'date-asc') selected @endif>{{__('Order by Date')}} &downarrow;</option>
                        <option value="date-desc" @if($filter['ordering'] == 'date-desc') selected @endif>{{__('Order by Date')}} &uparrow;</option>
                    </select>
                </div>
            </div>
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
                                    <th class="border-b dark:border-slate-600 font-medium p-3 hidden">#</th>
                                    <th class="border-b dark:border-slate-600 font-medium p-3 hidden">Shop</th>
                                    <th class="border-b dark:border-slate-600 font-medium p-3 whitespace-nowrap">Order #</th>
                                    <th class="border-b dark:border-slate-600 font-medium p-3">Order ID</th>
                                    <th class="border-b dark:border-slate-600 font-medium p-3 hidden">Payment</th>
                                    <th class="border-b dark:border-slate-600 font-medium p-3">Fulfillment Status</th>
                                    <th class="border-b dark:border-slate-600 font-medium p-3 text-right">Price</th>
                                    <th class="border-b dark:border-slate-600 font-medium p-3">Date</th>
                                    <th class="border-b dark:border-slate-600 font-medium p-3">Product</th>
                                    <th class="border-b dark:border-slate-600 font-medium p-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $k => $order)
                                <tr id="js_orderitem_{{$order->id}}" class="text-slate-500 dark:text-slate-400 hover:bg-lime-100 cursor-default">
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-3 hidden">{{ ($k+1) }}</td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-3 hidden">{{ $order->getShopName() }}</td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-3">{{ $order->data['name'] }}</td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-3">{{ $order->order_id }}</td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-3 hidden">{{ $order->payment_status }}</td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-3">{{ $order->fulfillment_status }}</td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-3 text-right">&pound;{{ $order->data['total_price'] }}</td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-3">{{ $order->updated_at->format('Y-m-d H:i') }}</td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-3">{!! $order->productName() !!}</td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-3">
                                        <div class="flex flex-nowrap">
                                            @if($order->finished == 0)
                                            <a href="{!! route('mysteryBoxCollect', [$order->id]) !!}"
                                                class="hover:bg-lime-600 bg-lime-500 focus:outline-none focus:ring focus:ring-lime-200 transition ease-in-out duration-150 flex items-center rounded-md text-white text-sm font-medium pl-2 pr-3 py-1 mr-2 shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                                                </svg>
                                                {{ __('Collect') }}
                                            </a>
                                            @endif
                                            @if($order->pdf_file)
                                                <a href="{!! url('downloads/', [$order->pdf_file]) !!}" target="_blank"
                                                   class="hover:bg-lime-600 bg-lime-500 focus:outline-none focus:ring focus:ring-lime-200 transition ease-in-out duration-150 flex items-center rounded-md text-white text-sm font-medium pl-2 pr-3 py-1 mr-2 shadow-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 h-5 w-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                    </svg>
                                                    {{ __('PDF') }}
                                                </a>
                                            @endif
                                            <button type="button" data-trigger="js_action_click" data-action="" data-id="{{$order->id}}" data-parent="#js_orderitem_{{$order->id}}"
                                                    class="hidden hover:bg-lime-600 bg-lime-500 focus:outline-none focus:ring focus:ring-lime-200 transition ease-in-out duration-150 flex items-center rounded-md text-white text-sm font-medium p-1 shadow-sm">
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

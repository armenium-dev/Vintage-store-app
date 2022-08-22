<?php
use App\Http\Helpers\Image;
?>
<x-app-layout>
	<x-slot name="header">
		<div class="">
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Mystery Box Orders') }}</h2>
		</div>
		<x-alert type="error" :message="session('status')"/>
	</x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="p-6 bg-white shadow-sm sm:rounded-lg">

				@if(empty($mystery_boxes))
					<h2 class="text-gray-200 font-bold drop-shadow-inset-1 text-center text-3xl">{{__('Not found')}}</h2>
				@else
					<ul class="js_container flex flex-wrap justify-center">
						@foreach($mystery_boxes as $k => $order)
							<li id="item_{!! $k !!}" class="w-full sm:w-1/2 lg:w-1/3 p-3">
								<div class="flex flex-col justify-between px-2 py-4 bg-white hover:bg-blue-100 dark:bg-blue-500 dark:hover:bg-blue-700 rounded-lg border border-gray-100 dark:border-blue-500 dark:hover:border-blue-700 shadow-md overflow-hidden h-full transition ease-out duration-200">
									<h2 class="px-3 pb-3"><b>Order {!! $order['name'] !!}</b><br>{!! $order['title'] !!}</h2>
									<div class="flex-1 flex flex-wrap content-start mb-3 h-full">
										@foreach($order['products'] as $item)
											<div class="w-1/2 p-2" title="{{$item['product_title']}}">
												<img src="{!! Image::letProductThumb($item['image']) !!}" class="block mx-auto w-full h-auto rounded-md border border-blue-100">
												<div class="pt-2 text-sm">
													<h3 class="pb-3 dark:text-white hidden">{{$item['product_title']}}</h3>
													<div class="flex flex-row flex-wrap justify-between items-center text-[12px]">
														<div class="font-bold dark:text-white flex w-[40%] relative">
															<span class="absolute left-[3px] top-[1px]">&pound;</span>
															<input name="{!! $item['product_id'] !!}" value="{!! $item['price'] !!}" class="js_price w-full pl-[10px] border border-blue-100">
														</div>
														<h3 class="font-bold text-blue-500 dark:text-white text-right w-[60%]">{{$item['tag']}}</h3>
													</div>
												</div>
											</div>
										@endforeach
									</div>
									<button type="button" data-trigger="js_action_click" data-action="pack_product" data-id="{!! $k !!}"
											class="group disabled:bg-gray-400 hover:bg-blue-600 dark:bg-white dark:hover:bg-gray-200 bg-blue-500 focus:outline-none focus:ring focus:ring-blue-200 transition ease-in-out duration-150 flex items-center rounded-md dark:text-blue-500 text-white text-sm font-medium block mx-auto pl-2 pr-3 py-1 shadow-sm">
										<svg xmlns="http://www.w3.org/2000/svg" class="group-disabled:hidden mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
											<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
										</svg>
										<svg class="hidden group-disabled:animate-spin group-disabled:block mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
											<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
											<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
										</svg>
										<span>{!! __('Done & Create PDF') !!}</span>
									</button>
								</div>
							</li>
						@endforeach
					</ul>
				@endif

			</div>
		</div>
	</div>


</x-app-layout>

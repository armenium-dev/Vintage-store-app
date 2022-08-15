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

				<div class="pb-5 border-b border-gray-200">
					<div class="flex items-center">
						<div class="pt-1 pb-1 mr-5 flex-grow-0"><img src="{{$product->image}}" class="max-w-120 sm:max-w-50 rounded-md"></div>
						<div class="flex-1 flex flex-col sm:flex-row -mr-2 -ml-2 justify-between font-bold">
							<div class="mr-2 ml-2 pt-1 pb-1 text-indigo-500">{{$product->title}}</div>
							<div class="mr-2 ml-2 pt-1 pb-1 text-blue-500">{{$variant->title}}</div>
							<div class="mr-2 ml-2 pt-1 pb-1 text-cyan-500">{{__('Order')}} {{$order->data['name']}}</div>
							<div class="mr-2 ml-2 pt-1 pb-1 text-teal-500">{{__('Price')}} &pound;{{$variant->price}}</div>
						</div>
					</div>
				</div>

				<form id="js_mbox_form" method="post" action="{{ route('storeOrderMysteryBox') }}">
					@csrf
					<div id="accordion-flush" data-accordion="collapse" data-active-classes="bg-white text-gray-900" data-inactive-classes="text-gray-500">
						@foreach($box_items as $k => $box)
							<h2 id="accordion-flush-heading-{{$k}}">
								<button type="button" class="flex items-center justify-between w-full py-5 font-medium text-left border-b border-gray-200 bg-white text-gray-900" data-accordion-target="#accordion-flush-body-{{$k}}" aria-expanded="true" aria-controls="accordion-flush-body-{{$k}}">
									<span class="text-{!! $box['color'] !!}-500 flex-1">{{$box['title']}}</span>
									<span class="mr-2 text-sm">Found: <span class="inline-block text-center p-[2px] min-w-[20px] text-xs font-bold text-white bg-{!! $box['color'] !!}-500 rounded-full">{!! count($box['items']) !!}</span></span>
									<span class="mr-2 text-sm">Max selection count: <span class="inline-block text-center p-[2px] min-w-[20px] text-xs font-bold text-white bg-{!! $box['color'] !!}-500 rounded-full">{!! $box['count'] !!}</span></span>
									<svg data-accordion-icon="" class="w-6 h-6 rotate-180 shrink-0 text-{!! $box['color'] !!}-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
								</button>
							</h2>
							<div id="accordion-flush-body-{{$k}}" class="" aria-labelledby="accordion-flush-heading-{{$k}}">
								<div class="py-5 font-light border-b border-gray-200 sm:max-h-70s sm:overflow-y-auto">
									@if(empty($box['items']))
										<h2 class="text-gray-200 font-bold drop-shadow-inset-1 text-center text-3xl">{{__('Not found')}}</h2>
									@else
										<ul class="js_container flex flex-wrap justify-center" data-choice-max-count="{!! $box['count'] !!}">
											@foreach($box['items'] as $item)
												<li id="item_{!! $item['id'] !!}" class="w-full xs:w-1/2 sm:w-1/3 md:w-1/4 lg:w-20p p-2 {!! ($item['exist'] == 0) ?: 'dark'!!}">
													<label class="flex flex-col justify-between bg-white hover:bg-{!! $box['color'] !!}-100 dark:bg-{!! $box['color'] !!}-500 dark:hover:bg-{!! $box['color'] !!}-700 rounded-lg border border-gray-100 dark:border-{!! $box['color'] !!}-500 dark:hover:border-{!! $box['color'] !!}-700 shadow-md overflow-hidden h-full cursor-pointer transition ease-out duration-200">
														<img src="{!! \App\Http\Helpers\Image::letProductThumb($item['image']) !!}" class="">
														<div class="p-3">
															<h2 class="pb-3 text-sm dark:text-white">{{$item['product_title']}}</h2>
															<div class="flex flex-row flex-nowrap justify-between">
																<h3 class="font-bold text-{!! $box['color'] !!}-500 dark:text-white">&pound;{{$item['price']}}</h3>
																<input type="checkbox" name="items[]" value="{!! $k !!}:{!! $order->order_id !!}:{!! $lid !!}:{!! $item['product_id'] !!}:{!! $item['variant_id'] !!}" {!! ($item['exist'] == 0) ?: 'checked="checked"'!!}
																	   class="text-{!! $box['color'] !!}-500 bg-white rounded border-0 border-{!! $box['color'] !!}-300 focus:ring-0 focus:ring-offset-0"
																	   data-trigger="js_action_change" data-action="choice_mb_product" data-parent="#item_{!! $item['id'] !!}">
															</div>
														</div>
													</label>
												</li>
											@endforeach
										</ul>
									@endif
								</div>
							</div>
						@endforeach
					</div>

					<div class="px-4 py-3 bg-gray-50 text-center sm:px-6">
						<input type="hidden" name="order_id" value="{!! $order->id !!}">
						<button type="submit" class="py-2 px-4 border border-transparent shadow-sm text-base font-medium rounded-md
						text-white hover:text-black disabled:text-gray-400 disabled:hover:text-gray-400 bg-emerald-500 hover:bg-emerald-300 disabled:bg-gray-300" disabled>{{ __('Save box items') }}</button>
					</div>

					<div class="bg-indigo-500 hover:bg-indigo-100 dark:bg-indigo-500 dark:hover:bg-indigo-700 dark:border-indigo-500 dark:hover:border-indigo-700 border-indigo-300"></div>
					<div class="dark:bg-blue-500 hover:bg-blue-100 dark:bg-blue-500 dark:hover:bg-blue-700 dark:border-blue-500 dark:hover:border-blue-700 border-blue-300"></div>
					<div class="bg-cyan-500 hover:bg-cyan-100 dark:bg-cyan-500 dark:hover:bg-cyan-700 dark:border-cyan-500 dark:hover:border-cyan-700 border-cyan-300"></div>
					<div class="bg-teal-500 hover:bg-teal-100 dark:bg-teal-500 dark:hover:bg-teal-700 dark:border-teal-500 dark:hover:border-teal-700 border-teal-300"></div>
				</form>

			</div>
		</div>
	</div>


</x-app-layout>

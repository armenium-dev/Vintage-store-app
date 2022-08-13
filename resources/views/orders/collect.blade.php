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
				<div class="flex flex-col sm:flex-row justify-center items-center sm:items-stretch">

                    @foreach($line_items as $item)
						<div class="max-w-sm bg-white rounded-lg border border-gray-200 shadow-md m-6 flex-1">
							<img class="rounded-t-lg" src="{{ $item['product_image'] }}" alt="">
							<div class="p-5">
								<h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $item['product_title'] }}</h5>
								<p class="mb-5 font-normal text-gray-700 dark:text-gray-400">{{ $item['variant_title'] }}</p>
								<div class="flex flex-row flex-nowrap justify-between items-center">
									<a href="{!! route('mysteryBoxCollectProducts', [$order->order_id, $item['line_id'], $item['product_id'], $item['variant_id']]) !!}" class="inline-flex items-center py-2 px-3 text-sm font-medium text-center text-white bg-indigo-700 rounded-lg hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800">
									Collect products
									<svg aria-hidden="true" class="ml-2 -mr-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
								</a>
									<span class="text-gray-500">Collected: <span class="text-indigo-500">{!! $item['collected_count'] !!}/{!! $item['collected_total'] !!}</span></span>
								</div>
							</div>
						</div>
                    @endforeach

				</div>
			</div>
		</div>
	</div>


</x-app-layout>

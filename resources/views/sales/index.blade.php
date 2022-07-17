<x-app-layout>
    <x-slot name="header">
        <div class="">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Sales on Shopify') }}</h2>
        </div>
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
            </div>
        @endif
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex">
                        <div class="flex-1 px-2">
                            <h2 class="mb-4 text-center font-bold uppercase text-lg">{{ env('SHOPIFY_SHOP_1_NAME') }}</h2>
                            <ol class="marker:text-sky-400 space-y-4 text-slate-500 list-decimal pl-4 text-sm">
                            @forelse($shop_1_sales as $sale)
                                <li class="border-b border-gray-200 last:border-0 mb-4 pb-4">
                                    @include('sales.partials.item')
                                </li>
                            @empty
                                <h3 class="text-center text-gray-300 min-h-95p flex justify-center items-center border border-gray-100">---- {{__('No sales')}} ----</h3>
                            @endforelse
                            </ol>
                        </div>
                        <div class="flex-1 px-2">
                            <h2 class="mb-4 text-center font-bold uppercase text-lg">{{ env('SHOPIFY_SHOP_2_NAME') }}</h2>
                            <ol class="marker:text-sky-400 space-y-4 text-slate-500 list-decimal pl-4 text-sm">
                            @forelse($shop_2_sales as $sale)
                                <li class="border-b border-gray-200 last:border-0 mb-4 pb-4">
                                    @include('sales.partials.item')
                                </li>
                            @empty
                                <h3 class="text-center text-gray-300 min-h-95p flex justify-center items-center border border-gray-100">---- {{__('No sales')}} ----</h3>
                            @endforelse
                            </ol>
                        </div>
                        <div class="flex-1 px-2">
                            <h2 class="mb-4 text-center font-bold uppercase text-lg">{{ env('SHOPIFY_SHOP_3_NAME') }}</h2>
                            <ol class="marker:text-sky-400 space-y-4 text-slate-500 list-decimal pl-4 text-sm">
                            @forelse($shop_3_sales as $sale)
                                <li class="border-b border-gray-200 last:border-0 mb-4 pb-4">
                                    @include('sales.partials.item')
                                </li>
                            @empty
                                <h3 class="text-center text-gray-300 min-h-95p flex justify-center items-center border border-gray-100">---- {{__('No sales')}} ----</h3>
                            @endforelse
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>

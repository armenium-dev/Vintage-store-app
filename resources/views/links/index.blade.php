<x-app-layout>
    <x-slot name="header">
        <div class="">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Sales on Shopify') }}</h2>
        </div>
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex">
                        <div class="flex-1">
                            <h2 class="mb-4 text-center font-bold uppercase text-lg">{{ env('SHOPIFY_SHOP_1_NAME') }}</h2>
                            @forelse($shop_1_links as $link)
                                @include('links.partials.item')
                            @empty
                                <h3 class="text-center">{{__('No links')}}</h3>
                            @endforelse
                        </div>
                        <div class="flex-1">
                            <h2 class="mb-4 text-center font-bold uppercase text-lg">{{ env('SHOPIFY_SHOP_2_NAME') }}</h2>
                            @forelse($shop_2_links as $link)
                                @include('links.partials.item')
                            @empty
                                <h3 class="text-center">{{__('No links')}}</h3>
                            @endforelse
                        </div>
                        <div class="flex-1">
                            <h2 class="mb-4 text-center font-bold uppercase text-lg">{{ env('SHOPIFY_SHOP_3_NAME') }}</h2>
                            @forelse($shop_3_links as $link)
                                @include('links.partials.item')
                            @empty
                                <h3 class="text-center">{{__('No links')}}</h3>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>

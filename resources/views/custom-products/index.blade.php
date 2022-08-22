<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row flex-nowrap align-middle justify-between">
            <div class="flex-auto"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Custom products') }}</h2></div>
            <div class="flex-none">
                <a href="{{ route('custom-products.create') }}" class="hover:bg-blue-400 group flex items-center rounded-md bg-blue-500 text-white text-sm font-medium pl-2 pr-3 py-2 shadow-sm">
                    <svg width="20" height="20" fill="currentColor" class="mr-2" aria-hidden="true">
                        <path d="M10 5a1 1 0 0 1 1 1v3h3a1 1 0 1 1 0 2h-3v3a1 1 0 1 1-2 0v-3H6a1 1 0 1 1 0-2h3V6a1 1 0 0 1 1-1Z" />
                    </svg>
                    Add New
                </a>
            </div>
        </div>
        <x-alert type="error" :message="session('status')"/>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 bg-white shadow-sm sm:rounded-lg">

                @if($products->count())
                    <table class="table-auto w-full">
                        <thead>
                            <tr class="text-slate-400 dark:text-slate-200 text-left cursor-default font-medium">
                                <th class="border-b dark:border-slate-600 p-3">#</th>
                                <th class="border-b dark:border-slate-600 p-3">Image</th>
                                <th class="border-b dark:border-slate-600 p-3">Title</th>
                                <th class="border-b dark:border-slate-600 p-3">Category</th>
                                <th class="border-b dark:border-slate-600 p-3">Size</th>
                                <th class="border-b dark:border-slate-600 p-3">Count</th>
                                <th class="border-b dark:border-slate-600 p-3 text-right">Price</th>
                                <th class="border-b dark:border-slate-600 p-3">Date</th>
                                <th class="border-b dark:border-slate-600 p-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $k => $product)
                            <tr id="js_product_{{$product->id}}" class="text-slate-500 dark:text-slate-400 hover:bg-indigo-100 cursor-default">
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3">{{ ($k+1) }}</td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3">{{ $product->image }}</td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3">{{ $product->title }}</td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3">{{ $product->category }}</td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3">{{ $product->size }}</td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3">{{ $product->count }}</td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3 text-right">&pound;{{ $product->price }}</td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3">{{ $product->updated_at->format('Y-m-d H:i') }}</td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3">
                                    <div class="flex flex-nowrap">
                                        <a href="{!! route('custom-products.edit', [$product->id]) !!}"
                                           class="hover:bg-indigo-600 bg-indigo-500 focus:outline-none focus:ring focus:ring-indigo-200 transition ease-in-out duration-150 flex items-center rounded-md text-white text-sm font-medium pl-2 pr-3 py-1 mr-2 shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                                            </svg>
                                            {{ __('Edit') }}
                                        </a>
                                        <button type="button" data-trigger="js_action_click" data-action="" data-id="{{$product->id}}" data-parent="#js_orderitem_{{$product->id}}"
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
                @else
                    <h2 class="text-gray-50 font-bold drop-shadow-inset-1 text-center text-3xl">{{__('Not found')}}</h2>
                @endif

            </div>
        </div>
    </div>

</x-app-layout>

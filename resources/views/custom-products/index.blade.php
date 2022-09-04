<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row flex-nowrap align-middle justify-between">
            <div class="flex-auto"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Repetitive products') }}</h2></div>
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
                                <th class="border-b dark:border-slate-600 p-3">ID</th>
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
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3">{{ ($product->id) }}</td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3">
                                    @if($product->image)
                                        <img src="{!! asset('storage/'.$product->image) !!}" class="max-w-[40px] max-h-[40px] w-full h-auto rounded" />
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 100 100" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                        </svg>
                                    @endif
                                </td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3 js_title">{{ $product->title }}</td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3">{{ $product->category }}</td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3">{{ $product->size }}</td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3">{{ $product->count }}</td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3 text-right">&pound;{{ $product->price }}</td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3">{{ $product->updated_at->format('Y-m-d H:i') }}</td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3">
                                    <div class="flex flex-nowrap">
                                        <a href="{!! route('custom-products.edit', [$product->id]) !!}"
                                           class="hover:bg-indigo-600 bg-indigo-500 focus:outline-none focus:ring focus:ring-indigo-200 transition ease-in-out duration-150 flex items-center rounded-md text-white text-sm font-medium pl-2 pr-3 py-1 mr-2 shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 h-5 w-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                            </svg>
                                            {{ __('Edit') }}
                                        </a>
                                        <button type="button" data-trigger="js_action_click" data-action="remove_custom_product" data-id="{{$product->id}}" data-parent="#js_product_{{$product->id}}"
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

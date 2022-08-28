<x-app-layout>
    <x-slot name="header">
        <div class="">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Import Order by ID') }}</h2>
        </div>
        <x-alert type="error" :message="session('status')"/>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6">

                <div class="shadow sm:rounded-md sm:overflow-hidden max-w-md mx-auto">
                    <form action="{{ route('storeOrderByID') }}" method="POST">
                        @csrf
                        <div class="px-4 py-5 bg-white space-y-6 sm:p-6">

                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm"> {{ __('Shop ID') }} </span>
                                <select name="shop_id" class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300">
                                    @foreach($shops as $shop_id => $shop_name)
                                        <option value="{!! $shop_id !!}">{{$shop_name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm"> {{ __('Order ID') }} </span>
                                <input type="text" name="order_id" class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300">
                            </div>

                        </div>
                        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">{{ __('Import') }}</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>


</x-app-layout>

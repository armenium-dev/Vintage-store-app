<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="hidden p-6 bg-white border-b border-gray-200 flex flex-row flex-nowrap items-center">
                    <a href="{{ route('salesOnShopify') }}" class="hover:bg-blue-400 group flex items-center rounded-md bg-blue-500 text-white text-sm font-medium pl-2 pr-3 py-2 mr-2 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd" />
                        </svg>
                        {{ __('Sales on Shopify') }}
                        <span class="bg-white text-red-500 rounded-full px-1 ml-2">{{ $shopify_count }}</span>
                    </a>
                    <a href="{{ route('salesOnDepop') }}" class="hover:bg-blue-400 group flex items-center rounded-md bg-blue-500 text-white text-sm font-medium pl-2 pr-3 py-2 mr-2 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd" />
                        </svg>
                        {{ __('Sales on Depop') }}
                        <span class="bg-white text-red-500 rounded-full px-1 ml-2">{{ $depop_count }}</span>
                    </a>
                    <a href="{{ route('salesOnAsos') }}" class="hover:bg-blue-400 group flex items-center rounded-md bg-blue-500 text-white text-sm font-medium pl-2 pr-3 py-2 mr-2 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd" />
                        </svg>
                        {{ __('Sales on Asos') }}
                        <span class="bg-white text-red-500 rounded-full px-1 ml-2">{{ $asos_count }}</span>
                    </a>
                    <a href="{{ route('uploadForm') }}" class="hover:bg-blue-400 group flex items-center rounded-md bg-blue-500 text-white text-sm font-medium pl-2 pr-3 py-2 mr-2 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        {{ __('Upload Files') }}
                    </a>
                    <a href="{{ route('listWebhooks') }}" class="hover:bg-blue-400 group flex items-center rounded-md bg-blue-500 text-white text-sm font-medium pl-2 pr-3 py-2 mr-2 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                        </svg>
                        {{ __('Webhooks list') }}
                    </a>
                </div>
                <div class="p-6 bg-white border-b border-gray-200 flex flex-row flex-nowrap items-center">
                    <table class="table-auto w-full">
                        <thead>
                            <tr class="text-left cursor-default bg-gray-100">
                                <th class="border-b dark:border-slate-600 font-medium p-3">{{__('Data type')}}</th>
                                <th class="border-b dark:border-slate-600 font-medium p-3 text-right">{{__('Remote DB')}}</th>
                                <th class="border-b dark:border-slate-600 font-medium p-3 text-right">{{__('Local DB')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-slate-400 dark:text-slate-200 text-left cursor-default bg-indigo-50">
                                <th colspan="3" class="border-b dark:border-slate-600 p-1 text-center font-bold">{{$statistic['titles'][1]}}</th>
                            </tr>
                            <tr class="text-slate-500 dark:text-slate-400 hover:bg-indigo-100 cursor-default">
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3">{{ __('Products count') }}</td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3 text-right">{{$statistic['remote'][1]['products_count']}}</td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3 text-right">{{$statistic['local'][1]['products_count']}}</td>
                            </tr>
                            <tr class="text-slate-500 dark:text-slate-400 hover:bg-indigo-100 cursor-default">
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3">{{ __('Variants count') }}</td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3 text-right">{{$statistic['remote'][1]['variants_count']}}</td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3 text-right">{{$statistic['local'][1]['variants_count']}}</td>
                            </tr>

                            <tr class="text-slate-400 dark:text-slate-200 text-left cursor-default bg-indigo-50">
                                <th colspan="3" class="border-b dark:border-slate-600 p-1 text-center font-bold">{{$statistic['titles'][2]}}</th>
                            </tr>
                            <tr class="text-slate-500 dark:text-slate-400 hover:bg-indigo-100 cursor-default">
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3">{{ __('Products count') }}</td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3 text-right">{{$statistic['remote'][2]['products_count']}}</td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3 text-right">{{$statistic['local'][2]['products_count']}}</td>
                            </tr>
                            <tr class="text-slate-500 dark:text-slate-400 hover:bg-indigo-100 cursor-default">
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3">{{ __('Variants count') }}</td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3 text-right">{{$statistic['remote'][2]['variants_count']}}</td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3 text-right">{{$statistic['local'][2]['variants_count']}}</td>
                            </tr>

                            <tr class="text-slate-400 dark:text-slate-200 text-left cursor-default bg-indigo-50">
                                <th colspan="3" class="border-b dark:border-slate-600 p-1 text-center font-bold">{{$statistic['titles'][3]}}</th>
                            </tr>
                            <tr class="text-slate-500 dark:text-slate-400 hover:bg-indigo-100 cursor-default">
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3">{{ __('Products count') }}</td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3 text-right">{{$statistic['remote'][3]['products_count']}}</td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3 text-right">{{$statistic['local'][3]['products_count']}}</td>
                            </tr>
                            <tr class="text-slate-500 dark:text-slate-400 hover:bg-indigo-100 cursor-default">
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3">{{ __('Variants count') }}</td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3 text-right">{{$statistic['remote'][3]['variants_count']}}</td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3 text-right">{{$statistic['local'][3]['variants_count']}}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="text-slate-400 dark:text-slate-200 text-left cursor-default bg-indigo-50">
                                <th colspan="3" class="border-b dark:border-slate-600 p-1 text-center font-bold">{{__('Totals')}}</th>
                            </tr>
                            <tr class="text-slate-500 dark:text-slate-400 hover:bg-indigo-100 cursor-default">
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3">{{ __('Total products') }}</td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3 text-right">{{$statistic['remote']['products_total']}}</td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3 text-right">{{$statistic['local']['products_total']}}</td>
                            </tr>
                            <tr class="text-slate-500 dark:text-slate-400 hover:bg-indigo-100 cursor-default">
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3">{{ __('Total variants') }}</td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3 text-right">{{$statistic['remote']['variants_total']}}</td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-3 text-right">{{$statistic['local']['variants_total']}}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

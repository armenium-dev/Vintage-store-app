<div id="js_saleitem_{{$sale->id}}" class="">
    <h4 class="uppercase font-bold flex justify-between mb-4">
    @if($sale->link_type == 'depop')
        <span>{{__('Depop')}}</span>
    @elseif($sale->link_type == 'asos')
        <span>{{__('Asos')}}</span>
    @endif
        <small>Order ID: {{ $sale->order_id }}</small>
    </h4>
    <div class="mb-4 flex justify-between items-start">
        <span id="js_copy_text_{{$sale->id}}" class="text-red">{{ $sale->link }}</span>
        <a role="button" data-trigger="js_action_click" data-action="copy_to_clipboard" data-id="{{$sale->id}}" data-source="#js_copy_text_{{$sale->id}}"
           title="{{ __('Copy link') }}"
           class="active:bg-indigo-500 active:text-white block rounded-md p-1 ml-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M7 9a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H9a2 2 0 01-2-2V9z" />
                <path d="M5 3a2 2 0 00-2 2v6a2 2 0 002 2V5h8a2 2 0 00-2-2H5z" />
            </svg>
        </a>
    </div>
    <div class="relative">
        <div class="flex justify-end">
            <button type="button" id="dropdownInformationButton_{{$sale->id}}" data-dropdown-toggle="dropdownInformation_{{$sale->id}}" class="text-indigo-500 hover:text-indigo-600 mr-5 bg-transparent font-medium rounded-md text-sm p-0 text-center flex items-center">
                {{ __('More Info') }}
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <button type="button" data-trigger="js_action_click" data-action="delete_sale" data-id="{{$sale->id}}" data-parent="#js_saleitem_{{$sale->id}}"
                    class="group disabled:bg-gray-400 hover:bg-indigo-600 bg-indigo-500 focus:outline-none focus:ring focus:ring-indigo-200 transition ease-in-out duration-150 flex items-center rounded-md text-white text-sm font-medium pl-2 pr-3 py-1 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="group-disabled:hidden mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                <svg class="hidden group-disabled:animate-spin group-disabled:block mr-2 h-5 w-5 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>{{ __('Remove') }}</span>
            </button>
        </div>
        <!-- Dropdown menu -->
        <div id="dropdownInformation_{{$sale->id}}" class="z-10 hidden bg-gray-50 divide-y divide-gray-100 rounded shadow-lg w-full dark:bg-gray-700 dark:divide-gray-600" data-popper-reference-hidden="" data-popper-escaped="" data-popper-placement="top">
            <table class="table-auto text-left text-sm bg-slate-50">
                <tbody>
                <tr class="border-b">
                    <th class="px-3 py-2 w-1/2">{{ __('Shop') }}</th>
                    <td class="px-3 py-2 w-1/2 bg-white">{{ $sale->getShopName() }}</td>
                </tr>
                <tr class="border-b">
                    <th class="px-3 py-2">{{ __('Product') }}</th>
                    <td class="px-3 py-2 bg-white">{{ $sale->getProductName() }}</td>
                </tr>
                <tr class="border-b">
                    <th class="px-3 py-2">{{ __('Variant') }}</th>
                    <td class="px-3 py-2 bg-white">{{ $sale->getVariantName() }}</td>
                </tr>
                <tr class="border-b">
                    <th class="px-3 py-2">{{ __('Order ID') }}</th>
                    <td class="px-3 py-2 bg-white">{{ $sale->order_id }}</td>
                </tr>
                <tr class="border-b">
                    <th class="px-3 py-2">{{ __('Order Date') }}</th>
                    <td class="px-3 py-2 bg-white">{{ $sale->getOrderAttribute('date') }}</td>
                </tr>
                <tr class="border-b">
                    <th class="px-3 py-2">{{ __('Order confirmed') }}</th>
                    <td class="px-3 py-2 bg-white">{{ $sale->getOrderAttribute('confirmed') }}</td>
                </tr>
                <tr class="border-b">
                    <th class="px-3 py-2">{{ __('Order payment status') }}</th>
                    <td class="px-3 py-2 bg-white">{{ $sale->getOrderAttribute('payment_status') }}</td>
                </tr>
                <tr class="border-b">
                    <th class="px-3 py-2">{{ __('Order fulfillment status') }}</th>
                    <td class="px-3 py-2 bg-white">{{ $sale->getOrderAttribute('fulfillment_status') }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

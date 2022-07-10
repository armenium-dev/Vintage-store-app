<div id="js_linkitem_{{$link->id}}" class="border-b border-gray-200 last:border-0 mb-3 pb-3">
    <ol class="marker:text-sky-400 space-y-3 text-slate-500 list-decimal pl-4 pb-3 text-sm">
        <li class="pb-3">
            <h4 class="uppercase font-bold">{{__('Depop')}}</h4>
            {{ $link->link_depop }}
        </li>
        <li>
            <h4 class="uppercase font-bold">{{__('Asos')}}</h4>
            {{ $link->link_asos }}
        </li>
    </ol>
    <div class="relative">
        <div class="flex justify-end">
            <button type="button" id="dropdownInformationButton_{{$link->id}}" data-dropdown-toggle="dropdownInformation_{{$link->id}}" class="text-green-500 hover:text-green-600 mr-5 bg-transparent font-medium rounded-md text-sm p-0 text-center flex items-center">
                {{ __('More Info') }}
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <button type="button" data-trigger="js_action_click" data-action="delete_link" data-id="{{$link->id}}" data-parent="#js_linkitem_{{$link->id}}"
                    class="group disabled:bg-gray-400 hover:bg-indigo-600 bg-indigo-500 focus:outline-none focus:ring focus:ring-indigo-200 transition ease-in-out duration-150 flex items-center rounded-md text-white text-sm font-medium px-3 py-2 shadow-sm">
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
        <div id="dropdownInformation_{{$link->id}}" class="z-10 hidden bg-gray-50 divide-y divide-gray-100 rounded shadow-lg w-full dark:bg-gray-700 dark:divide-gray-600" data-popper-reference-hidden="" data-popper-escaped="" data-popper-placement="top">
            <table class="table-auto text-left text-sm bg-slate-50">
                <tbody>
                <tr class="border-b">
                    <th class="px-3 py-2 w-1/2">{{ __('Shop') }}</th>
                    <td class="px-3 py-2 w-1/2 bg-white">{{ $link->getShopName() }}</td>
                </tr>
                <tr class="border-b">
                    <th class="px-3 py-2">{{ __('Product') }}</th>
                    <td class="px-3 py-2 bg-white">{{ $link->getProductName() }}</td>
                </tr>
                <tr class="border-b">
                    <th class="px-3 py-2">{{ __('Variant') }}</th>
                    <td class="px-3 py-2 bg-white">{{ $link->getVariantName() }}</td>
                </tr>
                <tr class="border-b">
                    <th class="px-3 py-2">{{ __('Order ID') }}</th>
                    <td class="px-3 py-2 bg-white">{{ $link->order_id }}</td>
                </tr>
                <tr class="border-b">
                    <th class="px-3 py-2">{{ __('Order Date') }}</th>
                    <td class="px-3 py-2 bg-white">{{ $link->getOrderAttribute('date') }}</td>
                </tr>
                <tr class="border-b">
                    <th class="px-3 py-2">{{ __('Order confirmed') }}</th>
                    <td class="px-3 py-2 bg-white">{{ $link->getOrderAttribute('confirmed') }}</td>
                </tr>
                <tr class="border-b">
                    <th class="px-3 py-2">{{ __('Order payment status') }}</th>
                    <td class="px-3 py-2 bg-white">{{ $link->getOrderAttribute('payment_status') }}</td>
                </tr>
                <tr class="border-b">
                    <th class="px-3 py-2">{{ __('Order fulfillment status') }}</th>
                    <td class="px-3 py-2 bg-white">{{ $link->getOrderAttribute('fulfillment_status') }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

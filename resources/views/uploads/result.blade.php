<x-app-layout>
    <x-slot name="header">
        <div class="">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Upload CSV files') }}</h2>
        </div>
        <x-alert type="error" :message="session('status')"/>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="max-w-md mx-auto text-center">
                        <div class="mb-3">
                            Shortly after processing the files in the "<a href="{{route('salesOnDepop')}}" class="underline">Sales on Depop</a>" and "<a href="{{route('salesOnAsos')}}" class="underline">Sales on Asos</a>" section, you can see the results.
                        </div>
                        <div class="font-bold">File processing will take approximately 1 minute.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>

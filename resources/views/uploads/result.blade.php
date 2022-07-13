<x-app-layout>
    <x-slot name="header">
        <div class="">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Upload CSV files') }}</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible text-center mb-10" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <h2 class="text-center font-bold mb-3">Shortly after processing the files, you will be redirected to the results page.</h2>
                    <div class="text-center">Or follow this <a href="{{route('linksDepopAsos')}}" class="underline">link</a> to see the results after processing the files.</div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row flex-nowrap align-middle justify-between">
            <div class="flex-auto"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Repetitive product create') }}</h2></div>
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
            <div class="p-6">

                <div class="shadow sm:rounded-md sm:overflow-hidden max-w-md mx-auto">
                    <form action="{!! route('custom-products.store') !!}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @include('custom-products.fields')
                    </form>
                </div>

            </div>
        </div>
    </div>

</x-app-layout>

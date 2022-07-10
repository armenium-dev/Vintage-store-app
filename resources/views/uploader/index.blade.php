<x-app-layout>
    <x-slot name="header">
        <div class="">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Upload CSV files') }}</h2>
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
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('doUploadCsv') }}" method="post" enctype="multipart/form-data">
                        <div class="columns-2 gap-5">
                            <div class="text-center">
                                <h3 class="mb-5">DEPOP CSV</h3>
                                <input type="file" class="px-4 py-3 rounded-full bg-gray-100" name="depop_csv_file">
                            </div>
                            <div class="text-center">
                                <h3 class="mb-5">ASOS CSV</h3>
                                <input type="file" class="px-4 py-3 rounded-full bg-gray-100" name="asos_csv_file">
                            </div>
                        </div>
                        <div class="text-center mt-10 flex justify-center">
                            <button type="submit"
                                    data-trigger="js_action_click" data-action="validate_uploader_form"
                                    class="hover:bg-blue-400 group flex items-center rounded-md bg-blue-500 text-white text-sm font-medium pl-3 pr-3 py-2 shadow-sm">
                                Continue
                                <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.293 15.707a1 1 0 010-1.414L14.586 10l-4.293-4.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    <path fill-rule="evenodd" d="M4.293 15.707a1 1 0 010-1.414L8.586 10 4.293 5.707a1 1 0 011.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
<div class="px-4 py-5 bg-white space-y-6 sm:p-6">
    <div class="row-file">
        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
            <div class="space-y-1 text-center">
                <img id="js_image_preview" src="@if(isset($product) && $product->image){!! asset('storage/'.$product->image) !!}@endif" />
                <div class="text-sm text-gray-600">
                    <label for="file-upload" class="block relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span>{{ __('Upload an Image') }}</span>
                        <input id="file-upload" name="image" type="file" class="sr-only" data-trigger="js_action_change" data-action="display_selected_files">
                    </label>
                </div>
                <p class="text-xs text-gray-500">{{__('PNG, JPG, GIF')}}</p>
                <span id="js_file_placeholder" class="file-placeholder"></span>
            </div>
        </div>
    </div>
    <div class="row-title">
        <div class="mt-1 flex rounded-md shadow-sm">
            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">{{ __('Title') }}</span>
            <input type="text" name="title" class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                   placeholder="Product name" value="@if(isset($product)){!! $product->title !!}@endif">
        </div>
    </div>
    <div class="row-category">
        <div class="mt-1 flex rounded-md shadow-sm">
            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">{{ __('Category') }}</span>
            <select name="category" class="border border-gray-300 text-gray-900 text-sm rounded-none rounded-r-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="0">Choose a category</option>
                @if(isset($product))
                    @foreach($categories as $category)
                        <option value="{!! $category !!}" @if($category == $product->category) selected @endif>{!! $category !!}</option>
                    @endforeach
                @else
                    @foreach($categories as $category)
                        <option value="{!! $category !!}">{!! $category !!}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="row-size">
        <div class="mt-1 flex rounded-md shadow-sm">
            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">{{ __('Size') }}</span>
            <select name="size" class="border border-gray-300 text-gray-900 text-sm rounded-none rounded-r-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="0" selected>Choose a size</option>
                @if(isset($product))
                    @foreach($sizes as $size)
                        <option value="{!! $size !!}" @if($size == $product->size) selected @endif>{!! $size !!}</option>
                    @endforeach
                @else
                    @foreach($sizes as $size)
                        <option value="{!! $size !!}">{!! $size !!}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="row-price">
        <div class="mt-1 flex rounded-md shadow-sm">
            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">{{ __('Price') }}</span>
            <input type="number" name="price" min="0" max="10000" step="0.01" class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                   placeholder="Product price" value="@if(isset($product)){!! $product->price !!}@endif">
        </div>
    </div>
    <div class="row-count">
        <div class="mt-1 flex rounded-md shadow-sm">
            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">{{ __('Count') }}</span>
            <input type="number" name="count" min="0" max="10000" step="1" class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                   placeholder="Product count" value="@if(isset($product)){!! $product->count !!}@endif">
        </div>
    </div>
</div>
<div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        {{ __('Save') }}
        <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10.293 15.707a1 1 0 010-1.414L14.586 10l-4.293-4.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            <path fill-rule="evenodd" d="M4.293 15.707a1 1 0 010-1.414L8.586 10 4.293 5.707a1 1 0 011.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
        </svg>
    </button>
</div>

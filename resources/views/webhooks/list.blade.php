<x-app-layout>
	<x-slot name="header">
		<div class="">
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Webhooks') }}</h2>
		</div>
		<x-alert type="error" :message="session('status')"/>
	</x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white shadow-sm sm:rounded-lg">
				<div class="p-6 bg-white border-b border-gray-200">
					<div class="lg:flex">
						<div class="flex-1 px-2 mb-5 lg:mb-0">
							<h2 class="mb-4 text-center font-bold uppercase text-lg">{{ __('Webhooks from DB') }}</h2>
							@php(dump($webhooks))
						</div>
						<div class="flex-1 px-2 mb-5 lg:mb-0">
							<h2 class="mb-4 text-center font-bold uppercase text-lg">{{ __('Installed Webhooks') }}</h2>
							@php(dump($installed_webhooks))
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


</x-app-layout>

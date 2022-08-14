<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-10 w-auto fill-current text-gray-600" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 lg:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ml-10 lg:flex dropdown-item">
                    <x-dropdown align="left">
                        <x-slot name="trigger">
                            <x-nav-link>
                                {{ __('Sales') }}
                                <span class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </x-nav-link>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('salesOnShopify')" :active="request()->routeIs('salesOnShopify')" class="flex justify-between items-center">
                                {{ __('on Shopify') }}
                                <x-sales-count type="shopify"/>
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('salesOnDepop')" :active="request()->routeIs('salesOnDepop')" class="flex justify-between items-center">
                                {{ __('on Depop') }}
                                <x-sales-count type="depop"/>
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('salesOnAsos')" :active="request()->routeIs('salesOnAsos')" class="flex justify-between items-center">
                                {{ __('on Asos') }}
                                <x-sales-count type="asos"/>
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('uploadForm')" :active="request()->routeIs('uploadForm')" class="flex justify-between items-center">
                                {{ __('Upload files') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ml-10 lg:flex dropdown-item">
                    <x-dropdown align="left">
                        <x-slot name="trigger">
                            <x-nav-link>
                                {{ __('Orders') }}
                                <span class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </x-nav-link>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('mysteryBox')" :active="request()->routeIs('mysteryBox')" class="flex justify-between items-center">
                                {{ __('Mystary Box') }}
                                <x-orders-count type="shop_1"/>
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('warehousePick')" :active="request()->routeIs('warehousePick')" class="flex justify-between items-center">
                                {{ __('Pick') }}
                                <x-warehouse-count type="pick"/>
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('warehousePack')" :active="request()->routeIs('warehousePack')" class="flex justify-between items-center">
                                {{ __('Pack') }}
                                <x-warehouse-count type="pack"/>
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('importOrderByID')" :active="request()->routeIs('importOrderByID')" class="flex justify-between items-center">
                                {{ __('Import By ID') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                </div>

            </div>

            <!-- Settings Dropdown -->
            <div class="hidden lg:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center lg:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('salesOnShopify')" :active="request()->routeIs('salesOnShopify')">
                {{ __('Sales on Shopify') }}
                <x-sales-count type="shopify"/>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('salesOnDepop')" :active="request()->routeIs('salesOnDepop')">
                {{ __('Sales on Depop') }}
                <x-sales-count type="depop"/>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('salesOnAsos')" :active="request()->routeIs('salesOnAsos')">
                {{ __('Sales on Asos') }}
                <x-sales-count type="asos"/>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('uploadForm')" :active="request()->routeIs('uploadForm')">
                {{ __('Upload Files') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

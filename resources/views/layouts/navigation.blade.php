<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">

    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <div class="flex">

                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">

                    @auth

                        <!-- Dashboard -->
                        <x-nav-link
                            :href="route('dashboard')"
                            :active="request()->routeIs('dashboard')"
                        >
                            {{ __('Dashboard') }}
                        </x-nav-link>

                        <!-- Thesis Groups -->
                        <x-nav-link
                            :href="route('thesis-groups.index')"
                            :active="request()->routeIs('thesis-groups.*')"
                        >
                            {{ __('Thesis Groups') }}
                        </x-nav-link>

                        <!-- Supervised Groups -->
                        @if (auth()->user()->supervisor)
                            <x-nav-link
                                :href="route('thesis-groups.supervised')"
                                :active="request()->routeIs('thesis-groups.supervised')"
                            >
                                {{ __('My Supervised Groups') }}
                            </x-nav-link>
                        @endif

                        <!-- Notifications -->
                        @php
                            $unreadCount = auth()->user()->unreadNotifications->count();
                        @endphp

                        <x-nav-link
                            :href="route('notifications.index')"
                            :active="request()->routeIs('notifications.index')"
                        >
                            {{ __('Notifications') }}

                            @if ($unreadCount > 0)
                                <span class="bg-red-600 text-white text-xs rounded-full px-2 py-0.5 ml-1">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </x-nav-link>

                    @endauth

                    <!-- Course Projects -->
                    <x-nav-link
                        :href="route('course-projects.index')"
                        :active="request()->routeIs('course-projects.*')"
                    >
                        {{ __('Course Projects') }}
                    </x-nav-link>

                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">

                @auth

                    <x-dropdown align="right" width="48">

                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                            >

                                <div>
                                    {{ Auth::user()->name }}
                                </div>

                                <div class="ms-1">
                                    <svg
                                        class="fill-current h-4 w-4"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>

                            </button>
                        </x-slot>

                        <x-slot name="content">

                            <!-- Profile -->
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <button
                                    type="submit"
                                    class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out"
                                >
                                    {{ __('Log Out') }}
                                </button>

                            </form>

                        </x-slot>

                    </x-dropdown>

                @else

                    <div class="space-x-4">

                        <a
                            href="{{ route('login') }}"
                            class="text-sm text-gray-700 hover:text-gray-900"
                        >
                            {{ __('Log in') }}
                        </a>

                        <a
                            href="{{ route('register') }}"
                            class="text-sm text-gray-700 hover:text-gray-900"
                        >
                            {{ __('Register') }}
                        </a>

                    </div>

                @endauth

            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">

                <button
                    @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                >

                    <svg
                        class="h-6 w-6"
                        stroke="currentColor"
                        fill="none"
                        viewBox="0 0 24 24"
                    >

                        <path
                            :class="{'hidden': open, 'inline-flex': ! open}"
                            class="inline-flex"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />

                        <path
                            :class="{'hidden': ! open, 'inline-flex': open}"
                            class="hidden"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />

                    </svg>

                </button>

            </div>

        </div>
    </div>


    <!-- Responsive Navigation Menu -->
    <div
        :class="{'block': open, 'hidden': ! open}"
        class="hidden sm:hidden"
    >

        <div class="pt-2 pb-3 space-y-1">

            @auth

                <!-- Dashboard -->
                <x-responsive-nav-link
                    :href="route('dashboard')"
                    :active="request()->routeIs('dashboard')"
                >
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>

                <!-- Thesis Groups -->
                <x-responsive-nav-link
                    :href="route('thesis-groups.index')"
                    :active="request()->routeIs('thesis-groups.*')"
                >
                    {{ __('Thesis Groups') }}
                </x-responsive-nav-link>

                <!-- Supervised Groups -->
                @if (auth()->user()->supervisor)
                    <x-responsive-nav-link
                        :href="route('thesis-groups.supervised')"
                        :active="request()->routeIs('thesis-groups.supervised')"
                    >
                        {{ __('My Supervised Groups') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Notifications -->
                @php
                    $unreadCount = auth()->user()->unreadNotifications->count();
                @endphp

                <x-responsive-nav-link
                    :href="route('notifications.index')"
                    :active="request()->routeIs('notifications.index')"
                >
                    {{ __('Notifications') }}

                    @if ($unreadCount > 0)
                        <span class="bg-red-600 text-white text-xs rounded-full px-2 py-0.5 ml-1">
                            {{ $unreadCount }}
                        </span>
                    @endif

                </x-responsive-nav-link>

            @endauth

            <!-- Course Projects -->
            <x-responsive-nav-link
                :href="route('course-projects.index')"
                :active="request()->routeIs('course-projects.*')"
            >
                {{ __('Course Projects') }}
            </x-responsive-nav-link>

        </div>


        <!-- Responsive Settings Options -->
        @auth

            <div class="pt-4 pb-1 border-t border-gray-200">

                <div class="px-4">

                    <div class="font-medium text-base text-gray-800">
                        {{ Auth::user()->name }}
                    </div>

                    <div class="font-medium text-sm text-gray-500">
                        {{ Auth::user()->email }}
                    </div>

                </div>


                <div class="mt-3 space-y-1">

                    <!-- Profile -->
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>


                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button
                            type="submit"
                            class="block w-full text-start px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                        >
                            {{ __('Log Out') }}
                        </button>

                    </form>

                </div>

            </div>

        @else

            <div class="pt-4 pb-1 border-t border-gray-200 px-4 space-y-1">

                <a
                    href="{{ route('login') }}"
                    class="block text-sm text-gray-700"
                >
                    {{ __('Log in') }}
                </a>

                <a
                    href="{{ route('register') }}"
                    class="block text-sm text-gray-700"
                >
                    {{ __('Register') }}
                </a>

            </div>

        @endauth

    </div>

</nav>
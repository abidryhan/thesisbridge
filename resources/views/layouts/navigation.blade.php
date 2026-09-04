<nav x-data="{ open: false }" class="sticky top-0 z-40 bg-white/95 backdrop-blur-xs border-b border-stone-200/80 shadow-2xs">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo & Academic Masthead -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
                        <x-application-logo class="block h-8 w-auto fill-current text-blue-700 transition group-hover:scale-105" />
                        <span class="font-heading font-bold text-xl text-stone-900 tracking-tight">
                            ThesisBridge
                        </span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 lg:space-x-8 sm:-my-px sm:ms-8 sm:flex items-center">
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
                            :active="request()->routeIs('thesis-groups.index') || request()->routeIs('thesis-groups.show') || request()->routeIs('thesis-groups.create') || request()->routeIs('thesis-groups.edit')"
                        >
                            {{ __('Thesis Groups') }}
                        </x-nav-link>

                        <!-- Supervised Groups (Supervisor Only) -->
                        @if (auth()->user()->supervisor)
                            <x-nav-link
                                :href="route('thesis-groups.supervised')"
                                :active="request()->routeIs('thesis-groups.supervised')"
                            >
                                {{ __('My Supervised Groups') }}
                            </x-nav-link>
                        @endif

                        <!-- Feature 19/20 & Tier 4 Item 14: Interactive Notification Popover -->
                        @php
                            $unreadCount = auth()->user()->unreadNotifications->count();
                            $latestUnread = auth()->user()->unreadNotifications()->latest()->take(5)->get();
                        @endphp

                        <div x-data="{ openDropdown: false }" class="relative inline-flex items-center h-full">
                            <!-- Popover Trigger Button -->
                            <button
                                @click="openDropdown = !openDropdown"
                                type="button"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg text-stone-600 hover:text-stone-900 hover:bg-stone-50 transition focus:outline-none"
                                :class="{ 'bg-stone-100 text-stone-900': openDropdown }"
                            >
                                <svg class="w-4 h-4 text-stone-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <span>{{ __('Notifications') }}</span>
                                @if ($unreadCount > 0)
                                    <span class="inline-flex items-center justify-center min-w-[18px] h-[18px] text-[10px] font-mono font-bold text-white bg-rose-600 rounded-full px-1.5 shadow-2xs">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </button>

                            <!-- Floating Notification Drawer -->
                            <div
                                x-cloak
                                x-show="openDropdown"
                                @click.outside="openDropdown = false"
                                @keydown.escape.window="openDropdown = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                                class="absolute right-0 top-full mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-xl border border-stone-200/90 z-50 overflow-hidden"
                            >
                                <!-- Drawer Header -->
                                <div class="px-4 py-3 border-b border-stone-100 flex items-center justify-between bg-stone-50/60">
                                    <div class="flex items-center gap-2">
                                        <span class="font-heading font-bold text-sm text-stone-900">Notifications</span>
                                        @if ($unreadCount > 0)
                                            <span class="text-[10px] font-bold font-mono text-rose-700 bg-rose-50 border border-rose-200 px-2 py-0.5 rounded-full">
                                                {{ $unreadCount }} new
                                            </span>
                                        @endif
                                    </div>
                                    <a href="{{ route('notifications.index') }}" class="text-[11px] font-semibold text-blue-700 hover:text-blue-900">
                                        View Inbox &rarr;
                                    </a>
                                </div>

                                <!-- Drawer Items Feed -->
                                <div class="max-h-80 overflow-y-auto divide-y divide-stone-100">
                                    @forelse ($latestUnread as $notification)
                                        @php
                                            $isDigest = ($notification->data['type'] ?? '') === 'weekly_supervisor_digest';
                                            $isEscalation = ($notification->data['type'] ?? '') === 'milestone_overdue_escalation';
                                        @endphp
                                        <div class="p-3.5 hover:bg-stone-50/70 transition flex items-start gap-3 text-xs">
                                            <!-- Category Icon -->
                                            <div class="w-7 h-7 rounded-lg shrink-0 flex items-center justify-center mt-0.5 {{ $isEscalation ? 'bg-rose-50 text-rose-700 border border-rose-200/60' : ($isDigest ? 'bg-blue-50 text-blue-700 border border-blue-200/60' : 'bg-amber-50 text-amber-700 border border-amber-200/60') }}">
                                                @if ($isEscalation)
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                @elseif ($isDigest)
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                @else
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                @endif
                                            </div>

                                            <!-- Content & One-Click Mark Action -->
                                            <div class="flex-1 min-w-0">
                                                <p class="text-stone-800 font-medium line-clamp-2 leading-snug">
                                                    {{ $notification->data['message'] ?? 'Notification alert' }}
                                                </p>
                                                <div class="flex items-center justify-between gap-2 mt-1.5 pt-1">
                                                    <span class="text-[10px] text-stone-400 font-mono">
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </span>
                                                    <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="text-[11px] font-semibold text-blue-700 hover:text-blue-900">
                                                            Mark as read &rarr;
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="py-8 px-4 text-center">
                                            <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 mx-auto flex items-center justify-center mb-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <p class="text-xs text-stone-600 font-medium">All caught up</p>
                                            <p class="text-[11px] text-stone-400 mt-0.5">No unread notifications in your queue.</p>
                                        </div>
                                    @endforelse
                                </div>

                                <!-- Drawer Footer -->
                                <div class="p-2.5 bg-stone-50 border-t border-stone-100 text-center">
                                    <a href="{{ route('notifications.index') }}" class="text-xs font-semibold text-stone-700 hover:text-stone-900 block py-1">
                                        Go to Full Notifications Inbox &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endauth

                    <!-- Course Projects (Public Showcase) -->
                    <x-nav-link
                        :href="route('course-projects.index')"
                        :active="request()->routeIs('course-projects.*')"
                    >
                        {{ __('Course Projects') }}
                    </x-nav-link>

                    <!-- Research Threads (Public Map) -->
                    <x-nav-link
                        :href="route('research-thread-map')"
                        :active="request()->routeIs('research-thread-map')"
                    >
                        {{ __('Research Threads') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings / User Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <x-dropdown align="right" width="56">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center gap-2.5 px-3 py-1.5 border border-stone-200/80 rounded-lg text-xs font-semibold text-stone-700 bg-stone-50/60 hover:bg-stone-100 hover:text-stone-900 focus:outline-none transition shadow-2xs"
                            >
                                <div class="w-6 h-6 rounded-full bg-blue-50 text-blue-700 border border-blue-200/60 flex items-center justify-center font-heading font-bold text-xs shrink-0">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>

                                <span class="max-w-[120px] truncate">{{ Auth::user()->name }}</span>

                                <svg class="fill-current h-3.5 w-3.5 text-stone-400" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Role Profiles -->
                            @if (auth()->user()->student)
                                <x-dropdown-link :href="route('students.show', auth()->user()->student)" class="font-medium flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    {{ __('My Student Profile') }}
                                </x-dropdown-link>
                            @elseif (auth()->user()->supervisor)
                                <x-dropdown-link :href="route('supervisors.show', auth()->user()->supervisor)" class="font-medium flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    {{ __('My Supervisor Profile') }}
                                </x-dropdown-link>
                            @endif

                            <!-- Account Profile Settings -->
                            <x-dropdown-link :href="route('profile.edit')" class="text-stone-600 flex items-center gap-2">
                                <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ __('Account Settings') }}
                            </x-dropdown-link>

                            <div class="border-t border-stone-100 my-1"></div>

                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="w-full text-start px-4 py-2 text-xs font-medium text-rose-700 hover:bg-rose-50 flex items-center gap-2 transition"
                                >
                                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    {{ __('Log Out') }}
                                </button>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <!-- Guest Links -->
                    <div class="flex items-center gap-3">
                        <a
                            href="{{ route('login') }}"
                            class="text-xs font-medium text-stone-700 hover:text-stone-900 px-2 py-1 transition"
                        >
                            {{ __('Log in') }}
                        </a>

                        <a
                            href="{{ route('register') }}"
                            class="inline-flex items-center text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 px-3.5 py-2 rounded-lg shadow-xs transition"
                        >
                            {{ __('Register') }}
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger Button (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button
                    @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-lg text-stone-400 hover:text-stone-600 hover:bg-stone-100 focus:outline-none transition"
                >
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Mobile Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-b border-stone-200 bg-white">
        <div class="pt-2 pb-3 space-y-1">
            @auth
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('thesis-groups.index')" :active="request()->routeIs('thesis-groups.*')">
                    {{ __('Thesis Groups') }}
                </x-responsive-nav-link>

                @if (auth()->user()->supervisor)
                    <x-responsive-nav-link :href="route('thesis-groups.supervised')" :active="request()->routeIs('thesis-groups.supervised')">
                        {{ __('My Supervised Groups') }}
                    </x-responsive-nav-link>
                @endif

                @php
                    $unreadCount = auth()->user()->unreadNotifications->count();
                @endphp

                <x-responsive-nav-link :href="route('notifications.index')" :active="request()->routeIs('notifications.index')" class="flex items-center justify-between">
                    <span>{{ __('Notifications') }}</span>
                    @if ($unreadCount > 0)
                        <span class="text-[10px] font-mono font-bold text-white bg-rose-600 rounded-full px-2 py-0.5">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </x-responsive-nav-link>
            @endauth

            <x-responsive-nav-link :href="route('course-projects.index')" :active="request()->routeIs('course-projects.*')">
                {{ __('Course Projects') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('research-thread-map')" :active="request()->routeIs('research-thread-map')">
                {{ __('Research Threads') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive User Settings -->
        @auth
            <div class="pt-4 pb-3 border-t border-stone-100 px-4">
                <div class="flex items-center gap-2.5 mb-3">
                    <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-700 border border-blue-200/60 flex items-center justify-center font-heading font-bold text-xs shrink-0">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-medium text-sm text-stone-900">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-stone-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="space-y-1">
                    @if (auth()->user()->student)
                        <x-responsive-nav-link :href="route('students.show', auth()->user()->student)">
                            {{ __('My Student Profile') }}
                        </x-responsive-nav-link>
                    @elseif (auth()->user()->supervisor)
                        <x-responsive-nav-link :href="route('supervisors.show', auth()->user()->supervisor)">
                            {{ __('My Supervisor Profile') }}
                        </x-responsive-nav-link>
                    @endif

                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Account Settings') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-start px-4 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-50 rounded-lg transition">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-3 border-t border-stone-100 px-4 space-y-2">
                <a href="{{ route('login') }}" class="block text-xs font-medium text-stone-700 py-1">
                    {{ __('Log in') }}
                </a>
                <a href="{{ route('register') }}" class="block text-center text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 py-2 rounded-lg transition">
                    {{ __('Register') }}
                </a>
            </div>
        @endauth
    </div>
</nav>

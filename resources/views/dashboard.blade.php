<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-8 px-4">
        @if (session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">{{ session('error') }}</div>
        @endif

        @if (!$student && !$supervisor)
            <div class="bg-white shadow rounded-lg p-8 text-center">
                <h3 class="text-xl font-semibold mb-2">Welcome to ThesisBridge!</h3>
                <p class="text-gray-600 mb-6">To get started, let us know who you are.</p>
                <div class="flex justify-center gap-4">
                    <a href="{{ route('students.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded font-medium">
                        I'm a Student
                    </a>
                    <a href="{{ route('supervisors.create') }}" class="bg-green-600 text-white px-6 py-3 rounded font-medium">
                        I'm a Supervisor
                    </a>
                </div>
            </div>
        @elseif ($student)
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-1">Welcome back, {{ auth()->user()->name }} (Student)</h3>
                <a href="{{ route('students.show', $student) }}" class="text-blue-600 text-sm underline">View My Profile</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @if ($thesisGroup)
                    <a href="{{ route('thesis-groups.show', $thesisGroup) }}" class="bg-white shadow rounded-lg p-5 hover:bg-gray-50">
                        <h4 class="font-semibold mb-1">My Thesis Group</h4>
                        <p class="text-sm text-gray-500">{{ $thesisGroup->group_name }}</p>
                    </a>
                @else
                    <a href="{{ route('thesis-groups.create') }}" class="bg-white shadow rounded-lg p-5 hover:bg-gray-50">
                        <h4 class="font-semibold mb-1">Form a Thesis Group</h4>
                        <p class="text-sm text-gray-500">You're not in a group yet — start one here.</p>
                    </a>
                @endif

                <a href="{{ route('course-projects.create') }}" class="bg-white shadow rounded-lg p-5 hover:bg-gray-50">
                    <h4 class="font-semibold mb-1">Submit a Course Project</h4>
                    <p class="text-sm text-gray-500">Add a new project to the showcase.</p>
                </a>

                <a href="{{ route('course-projects.index') }}" class="bg-white shadow rounded-lg p-5 hover:bg-gray-50">
                    <h4 class="font-semibold mb-1">Browse Course Projects</h4>
                    <p class="text-sm text-gray-500">See what others have submitted.</p>
                </a>

                <a href="{{ route('research-thread-map') }}" class="bg-white shadow rounded-lg p-5 hover:bg-gray-50">
                    <h4 class="font-semibold mb-1">Research Thread Map</h4>
                    <p class="text-sm text-gray-500">Explore connected academic work department-wide.</p>
                </a>
            </div>
        @else
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-1">Welcome back, {{ auth()->user()->name }} (Supervisor)</h3>
                <a href="{{ route('supervisors.show', $supervisor) }}" class="text-blue-600 text-sm underline">View My Profile</a>
            </div>

            @if ($ghostGroups->isNotEmpty())
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <h4 class="font-semibold text-red-800 mb-2">⚠ {{ $ghostGroups->count() }} group(s) need attention</h4>
                    <ul class="text-sm text-red-700 space-y-1">
                        @foreach ($ghostGroups as $group)
                            <li>
                                <a href="{{ route('thesis-groups.show', $group) }}" class="underline">{{ $group->group_name }}</a>
                                — no student activity in {{ $group->daysSinceLastActivity() }} days
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="{{ route('thesis-groups.supervised') }}" class="bg-white shadow rounded-lg p-5 hover:bg-gray-50">
                    <h4 class="font-semibold mb-1">My Supervised Groups</h4>
                    <p class="text-sm text-gray-500">View your active groups and any ghost flags.</p>
                </a>

                <a href="{{ route('thesis-groups.index') }}" class="bg-white shadow rounded-lg p-5 hover:bg-gray-50">
                    <h4 class="font-semibold mb-1">Browse All Thesis Groups</h4>
                    <p class="text-sm text-gray-500">See every group across the department.</p>
                </a>

                <a href="{{ route('course-projects.index') }}" class="bg-white shadow rounded-lg p-5 hover:bg-gray-50">
                    <h4 class="font-semibold mb-1">Browse Course Projects</h4>
                    <p class="text-sm text-gray-500">See what students have submitted.</p>
                </a>

                <a href="{{ route('research-thread-map') }}" class="bg-white shadow rounded-lg p-5 hover:bg-gray-50">
                    <h4 class="font-semibold mb-1">Research Thread Map</h4>
                    <p class="text-sm text-gray-500">Explore connected academic work department-wide.</p>
                </a>
            </div>
        @endif
    </div>
</x-app-layout>

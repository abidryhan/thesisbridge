<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Your Proposal
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4">
        @if (session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
        @endif

        <div class="mb-4">
            <span class="inline-block bg-gray-200 px-3 py-1 rounded text-sm font-medium">
                Status: {{ ucfirst($proposal->status) }}
            </span>
        </div>

        <p class="text-gray-500 mb-6">
            Submitted by: {{ $proposal->thesisGroup->group_name }}
            &middot;
            Supervisor: {{ $proposal->thesisGroup->supervisor->user->name ?? 'Not assigned yet' }}
        </p>

        @if (!empty($proposal->research_tags))
            <div class="mb-6">
                <h3 class="font-semibold mb-1">Research Tags</h3>
                <p>{{ implode(', ', $proposal->research_tags) }}</p>
            </div>
        @endif


        <div class="mb-6">
            <h3 class="font-semibold mb-1">Title</h3>
            <p>{{ $proposal->title }}</p>
        </div>

        <div class="mb-6">
            <h3 class="font-semibold mb-1">Abstract</h3>
            <p>{{ $proposal->abstract }}</p>
        </div>

        <div class="mb-6">
            <h3 class="font-semibold mb-1">Objectives</h3>
            <p>{{ $proposal->objectives }}</p>
        </div>

        <div class="mb-6">
            <h3 class="font-semibold mb-1">Methodology</h3>
            <p>{{ $proposal->methodology }}</p>
        </div>

        <a href="{{ route('thesis-groups.show', $proposal->thesisGroup) }}" class="text-blue-600 underline">
            Back to Group
        </a>
    </div>
</x-app-layout>

@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8 px-4">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Supervisor Matches</h1>
        <p class="text-gray-600 mt-2">
            Recommended supervisors for <span class="font-semibold">{{ $group->group_name }}</span>
        </p>
    </div>

    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-3">Proposal</h2>
        <p class="text-gray-700 mb-4">{{ $proposal->title }}</p>

        <div class="flex flex-wrap gap-2">
            @forelse($proposal->research_tags ?? [] as $tag)
                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">
                    {{ $tag }}
                </span>
            @empty
                <span class="text-gray-500 text-sm">No research tags provided.</span>
            @endforelse
        </div>
    </div>

    @if($matches->isEmpty())
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg">
            No supervisor matches found.
        </div>
    @else
        <div class="space-y-4">
            @foreach($matches as $match)
                @php
                    $supervisor = $match['supervisor'];
                    $areas = $supervisor->research_areas ?? [];
                    $remaining = max(0, $supervisor->max_capacity - ($supervisor->current_students ?? 0));
                @endphp

                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ $supervisor->user->name ?? 'Supervisor' }}
                            </h3>

                            <p class="text-sm text-gray-600 mt-1">
                                Capacity: {{ $supervisor->current_students ?? 0 }} / {{ $supervisor->max_capacity }}
                                ({{ $remaining }} remaining)
                            </p>
                        </div>

                        <div class="text-right">
                            <div class="text-2xl font-bold text-green-600">
                                {{ $match['matchCount'] }}
                            </div>
                            <div class="text-sm text-gray-500">matching tags</div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Research Areas</h4>

                        <div class="flex flex-wrap gap-2">
                            @forelse($areas as $area)
                                <span class="px-3 py-1 bg-gray-100 text-gray-800 text-sm rounded-full">
                                    {{ $area }}
                                </span>
                            @empty
                                <span class="text-gray-500 text-sm">No research areas listed.</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="mt-8">
        <a href="{{ route('thesis-groups.show', $group) }}"
           class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700">
            ← Back to Thesis Group
        </a>
    </div>
</div>
@endsection
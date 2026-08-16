<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $proposal->title }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4">
        @if (session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">{{ session('error') }}</div>
        @endif

        @php
            $statusClasses = match ($proposal->status) {
                'approved' => 'bg-green-100 text-green-800',
                'rejected' => 'bg-red-100 text-red-800',
                'revision_required' => 'bg-yellow-100 text-yellow-800',
                'under_review' => 'bg-blue-100 text-blue-800',
                default => 'bg-gray-200 text-gray-700',
            };
        @endphp

        <span class="inline-block text-sm px-3 py-1 rounded font-medium mb-4 {{ $statusClasses }}">
            {{ ucfirst(str_replace('_', ' ', $proposal->status)) }}
        </span>

        <div class="mb-6">
            <h3 class="font-semibold mb-1">Abstract</h3>
            <p class="text-gray-700">{{ $proposal->abstract }}</p>
        </div>

        <div class="mb-6">
            <h3 class="font-semibold mb-1">Objectives</h3>
            <p class="text-gray-700">{{ $proposal->objectives }}</p>
        </div>

        <div class="mb-6">
            <h3 class="font-semibold mb-1">Methodology</h3>
            <p class="text-gray-700">{{ $proposal->methodology }}</p>
        </div>

        @if (!empty($proposal->research_tags))
            <div class="mb-6">
                <h3 class="font-semibold mb-1">Research Tags</h3>
                <p>{{ implode(', ', $proposal->research_tags) }}</p>
            </div>
        @endif

        {{-- Supervisor review actions --}}
        @if ($isAssignedSupervisor)
            <div class="border rounded p-4 mb-6 bg-gray-50">
                <h3 class="font-semibold mb-3">Review Actions</h3>

                @if ($proposal->canTransitionTo('under_review'))
                    <form method="POST" action="{{ route('proposals.start-review', $proposal) }}" class="mb-3">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                            Move to Under Review
                        </button>
                    </form>
                @endif

                @if ($proposal->canTransitionTo('approved'))
                    <form method="POST" action="{{ route('proposals.approve', $proposal) }}" class="mb-3">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">
                            Approve
                        </button>
                    </form>
                @endif

                @if ($proposal->canTransitionTo('revision_required'))
                    <form method="POST" action="{{ route('proposals.request-revision', $proposal) }}" class="mb-3">
                        @csrf
                        @method('PATCH')
                        <textarea
                            name="reason"
                            rows="2"
                            required
                            placeholder="Explain what needs revision (required)"
                            class="w-full border rounded px-3 py-2 mb-2"
                        ></textarea>

                        <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded">
                            Request Revision
                        </button>
                    </form>
                @endif

                @if ($proposal->canTransitionTo('rejected'))
                    <form method="POST" action="{{ route('proposals.reject', $proposal) }}">
                        @csrf
                        @method('PATCH')
                        <textarea
                            name="reason"
                            rows="2"
                            required
                            placeholder="Explain the reason for rejection (required)"
                            class="w-full border rounded px-3 py-2 mb-2"
                        ></textarea>

                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">
                            Reject
                        </button>
                    </form>
                @endif
            </div>
        @endif

        {{-- Student-facing edit/resubmit --}}
        @if ($isMember && in_array($proposal->status, ['revision_required', 'rejected']))
            <div class="border rounded p-4 mb-6 bg-gray-50">
                <a
                    href="{{ route('proposals.edit', $proposal) }}"
                    class="bg-blue-600 text-white px-4 py-2 rounded inline-block mb-3"
                >
                    Edit Proposal
                </a>

                <form method="POST" action="{{ route('proposals.resubmit', $proposal) }}">
                    @csrf
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">
                        Resubmit for Review
                    </button>
                </form>
            </div>
        @endif

        {{-- Status history timeline --}}
        <div>
            <h3 class="font-semibold mb-2">Status History</h3>

            @forelse ($proposal->statusHistory as $entry)
                <div class="border-l-2 border-gray-300 pl-3 mb-3">
                    <p class="text-sm font-medium">
                        {{ ucfirst(str_replace('_', ' ', $entry->status)) }}
                    </p>

                    @if ($entry->reason)
                        <p class="text-sm text-gray-600">{{ $entry->reason }}</p>
                    @endif

                    <p class="text-xs text-gray-400">
                        {{ $entry->changedBy->name ?? 'A user who has since been removed' }}
                        &middot;
                        {{ $entry->created_at->format('M d, Y g:i A') }}
                    </p>
                </div>
            @empty
                <p class="text-gray-500 text-sm">No status changes recorded yet.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
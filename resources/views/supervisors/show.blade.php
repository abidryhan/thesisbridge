<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-heading font-bold text-2xl text-stone-900 leading-tight">
                    Supervisor Profile
                </h2>
                <p class="text-xs text-stone-500 mt-1">Faculty supervision profile &amp; verified track record</p>
            </div>
            <a href="{{ route('supervisors.edit', $supervisor) }}" class="inline-flex items-center text-xs font-medium text-stone-700 bg-white hover:bg-stone-50 border border-stone-200 px-3.5 py-2 rounded-lg shadow-xs transition">
                Edit Profile
            </a>
        </div>
    </x-slot>

    <x-container size="narrow">
        <!-- Faculty Information Card -->
        <x-card class="mb-6">
            <x-slot name="header">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-blue-50 border border-blue-200/60 flex items-center justify-center text-blue-700 font-heading font-bold text-lg">
                        {{ strtoupper(substr($supervisor->user->name ?? 'F', 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="font-heading font-bold text-xl text-stone-900">
                            {{ $supervisor->user->name ?? 'Supervisor Profile' }}
                        </h3>
                        <p class="text-xs text-blue-700 font-medium">{{ $supervisor->designation }}</p>
                    </div>
                </div>
            </x-slot>

            <!-- Live Capacity Callout -->
            <div class="grid grid-cols-2 gap-4 py-3 border-y border-stone-100 my-4 text-sm">
                <div>
                    <span class="text-xs font-medium text-stone-400 uppercase tracking-wider block">Supervision Load</span>
                    <span class="text-base font-semibold text-stone-900 mt-0.5 block">
                        {{ $supervisor->currentLoad() }} / {{ $supervisor->max_capacity }} groups
                    </span>
                </div>
                <div>
                    <span class="text-xs font-medium text-stone-400 uppercase tracking-wider block">Available Slots</span>
                    @php $openSlots = max(0, $supervisor->max_capacity - $supervisor->currentLoad()); @endphp
                    <span class="text-base font-semibold {{ $openSlots > 0 ? 'text-emerald-700' : 'text-rose-700' }} mt-0.5 block">
                        {{ $openSlots }} open
                    </span>
                </div>
            </div>

            <div class="mt-4">
                <span class="text-xs font-medium text-stone-400 uppercase tracking-wider block mb-2">Research Areas</span>
                <div class="flex flex-wrap gap-1.5">
                    @forelse ($supervisor->research_areas ?? [] as $area)
                        <x-badge variant="brand" size="md">{{ $area }}</x-badge>
                    @empty
                        <span class="text-stone-400 text-xs italic">No research areas specified.</span>
                    @endforelse
                </div>
            </div>
        </x-card>

        <!-- Feature 16: Track Record Metrics Card -->
        <x-card title="Supervision Track Record" subtitle="Computed live from resolved milestones and completed thesis journeys" class="mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5 pt-2">
                <div class="p-4 rounded-xl bg-stone-50/80 border border-stone-100">
                    <span class="text-xs font-medium text-stone-500 uppercase tracking-wider block">Avg Completion Time</span>
                    <span class="text-xl font-heading font-bold text-stone-900 mt-1 block">
                        {{ $averageCompletionTime !== null ? round($averageCompletionTime) . ' days' : '—' }}
                    </span>
                    <span class="text-[11px] text-stone-400 mt-0.5 block">
                        {{ $averageCompletionTime !== null ? 'Approval to final milestone' : 'Not enough completed theses yet' }}
                    </span>
                </div>

                <div class="p-4 rounded-xl bg-stone-50/80 border border-stone-100">
                    <span class="text-xs font-medium text-stone-500 uppercase tracking-wider block">Milestone Adherence</span>
                    <div class="flex items-baseline gap-2 mt-1">
                        <span class="text-xl font-heading font-bold {{ $adherenceRate !== null && $adherenceRate >= 80 ? 'text-emerald-700' : 'text-stone-900' }}">
                            {{ $adherenceRate !== null ? $adherenceRate . '%' : '—' }}
                        </span>
                        <span class="text-xs text-stone-400">
                            ({{ $totalSupervised }} group{{ $totalSupervised === 1 ? '' : 's' }} all-time)
                        </span>
                    </div>
                    <span class="text-[11px] text-stone-400 mt-0.5 block">
                        {{ $adherenceRate !== null ? 'On-time milestone deliveries' : 'No resolved milestones yet' }}
                    </span>
                </div>
            </div>

            <div>
                <span class="text-xs font-medium text-stone-500 uppercase tracking-wider block mb-2">Theses Supervised by Area</span>
                <div class="flex flex-wrap gap-2">
                    @forelse ($researchAreasCovered as $area => $count)
                        <x-badge variant="neutral" size="md">
                            <span>{{ $area }}</span>
                            <span class="ml-1.5 pl-1.5 border-l border-stone-300 text-stone-800 font-semibold">{{ $count }}</span>
                        </x-badge>
                    @empty
                        <p class="text-stone-400 text-xs italic py-1">No supervised groups with research tags recorded yet.</p>
                    @endforelse
                </div>
            </div>
        </x-card>

        <!-- Danger Zone (Delete with Alpine Modal) -->
        <div class="flex items-center justify-between px-2 pt-2">
            <span class="text-xs text-stone-400">Permanently remove supervisor profile</span>
            <x-delete-modal
                :action="route('supervisors.destroy', $supervisor)"
                title="Delete Supervisor Profile"
                message="Are you sure you want to delete this supervisor profile? This will unassign any currently supervised thesis groups."
                buttonText="Delete Profile"
            />
        </div>
    </x-container>
</x-app-layout>

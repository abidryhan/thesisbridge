<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-heading font-bold text-2xl text-stone-900 leading-tight">
                    Edit Thesis Group
                </h2>
                <p class="text-xs text-stone-500 mt-1">Modify group details and team composition</p>
            </div>
            <a href="{{ route('thesis-groups.show', $group) }}" class="inline-flex items-center text-xs font-medium text-stone-600 hover:text-stone-900 bg-white border border-stone-200 px-3.5 py-2 rounded-lg shadow-xs transition">
                &larr; Back to Group
            </a>
        </div>
    </x-slot>

    <x-container size="narrow">
        <x-card>
            <form
                method="POST"
                action="{{ route('thesis-groups.update', $group) }}"
                x-data="{
                    selected: {{ json_encode(array_map('strval', old('student_ids', $currentMemberIds))) }},
                    minAllowed: 2,
                    maxAllowed: 4,
                    get totalMembers() {
                        return this.selected.length;
                    },
                    get isValid() {
                        return this.totalMembers >= this.minAllowed && this.totalMembers <= this.maxAllowed;
                    }
                }"
            >
                @csrf
                @method('PUT')

                <!-- Group Name -->
                <div class="mb-5">
                    <label for="group_name" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">
                        Group Name
                    </label>
                    <input
                        type="text"
                        name="group_name"
                        id="group_name"
                        value="{{ old('group_name', $group->group_name) }}"
                        class="w-full border-stone-200 rounded-lg px-3.5 py-2.5 text-sm focus:border-blue-600 focus:ring-blue-600"
                    >
                    @error('group_name')
                        <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Supervisor Dropdown -->
                <div class="mb-6">
                    <label for="supervisor_id" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">
                        Assigned Supervisor (Optional)
                    </label>
                    <select
                        name="supervisor_id"
                        id="supervisor_id"
                        class="w-full border-stone-200 rounded-lg px-3.5 py-2.5 text-sm focus:border-blue-600 focus:ring-blue-600 bg-white"
                    >
                        <option value="">— No supervisor —</option>
                        @foreach ($supervisors as $supervisor)
                            <option
                                value="{{ $supervisor->id }}"
                                {{ old('supervisor_id', $group->supervisor_id) == $supervisor->id ? 'selected' : '' }}
                            >
                                {{ $supervisor->user->name ?? 'Supervisor #' . $supervisor->id }}
                                — {{ $supervisor->designation }}
                            </option>
                        @endforeach
                    </select>
                    @error('supervisor_id')
                        <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Member Roster with Alpine Counter -->
                <div class="mb-6">
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider">
                            Members (2–4 Students Required)
                        </label>

                        <!-- Dynamic Status Indicator -->
                        <div>
                            <template x-if="totalMembers < minAllowed">
                                <span class="inline-flex items-center text-[11px] font-medium text-amber-800 bg-amber-50 border border-amber-200 px-2.5 py-1 rounded-md">
                                    Need at least <span x-text="minAllowed"></span> (<span x-text="totalMembers"></span> selected)
                                </span>
                            </template>
                            <template x-if="isValid">
                                <span class="inline-flex items-center text-[11px] font-semibold text-emerald-800 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-md">
                                    ✓ Valid size: <span x-text="totalMembers"></span> of 4 members
                                </span>
                            </template>
                        </div>
                    </div>

                    @error('student_ids')
                        <p class="text-rose-600 text-xs mb-2">{{ $message }}</p>
                    @enderror

                    <div class="border border-stone-200 rounded-xl p-3 max-h-64 overflow-y-auto space-y-1.5 bg-stone-50/50">
                        @foreach ($selectableStudents as $student)
                            @php
                                $studentIdStr = (string) $student->id;
                            @endphp
                            <label
                                class="flex items-center justify-between p-2.5 rounded-lg border border-transparent transition cursor-pointer"
                                :class="{
                                    'bg-white border-blue-200 shadow-2xs': selected.includes('{{ $studentIdStr }}'),
                                    'hover:bg-white hover:border-stone-200': !selected.includes('{{ $studentIdStr }}'),
                                    'opacity-40 cursor-not-allowed': selected.length >= maxAllowed && !selected.includes('{{ $studentIdStr }}')
                                }"
                            >
                                <div class="flex items-center gap-3">
                                    <input
                                        type="checkbox"
                                        name="student_ids[]"
                                        value="{{ $student->id }}"
                                        x-model="selected"
                                        :disabled="selected.length >= maxAllowed && !selected.includes('{{ $studentIdStr }}')"
                                        class="rounded border-stone-300 text-blue-700 focus:ring-blue-600 h-4 w-4"
                                    >
                                    <div class="text-xs">
                                        <span class="font-medium text-stone-900 block">
                                            {{ $student->user->name ?? 'Student #' . $student->id }}
                                        </span>
                                        <span class="text-stone-400 text-[11px]">
                                            {{ $student->department ?? 'CS' }} &middot; Batch {{ $student->batch ?? 'N/A' }}
                                        </span>
                                    </div>
                                </div>

                                <template x-if="selected.includes('{{ $studentIdStr }}')">
                                    <span class="text-blue-700 font-semibold text-[10px] uppercase tracking-wider pr-1">Selected</span>
                                </template>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Form Controls -->
                <div class="flex items-center justify-between pt-5 border-t border-stone-100">
                    <a href="{{ route('thesis-groups.show', $group) }}" class="text-xs font-medium text-stone-500 hover:text-stone-700">
                        Cancel
                    </a>
                    <button
                        type="submit"
                        class="inline-flex items-center text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 px-5 py-2.5 rounded-lg shadow-xs transition"
                    >
                        Update Thesis Group
                    </button>
                </div>
            </form>
        </x-card>
    </x-container>
</x-app-layout>

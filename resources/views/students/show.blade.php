<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-heading font-bold text-2xl text-stone-900 leading-tight">
                    Student Profile
                </h2>
                <p class="text-xs text-stone-500 mt-1">Academic record and verified activity fingerprint</p>
            </div>
            <a href="{{ route('students.edit', $student) }}" class="inline-flex items-center text-xs font-medium text-stone-700 bg-white hover:bg-stone-50 border border-stone-200 px-3.5 py-2 rounded-lg shadow-xs transition">
                Edit Profile
            </a>
        </div>
    </x-slot>

    <x-container size="narrow">
        <!-- Core Profile Information -->
        <x-card class="mb-6">
            <x-slot name="header">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-blue-50 border border-blue-200/60 flex items-center justify-center text-blue-700 font-heading font-bold text-lg">
                        {{ strtoupper(substr($student->user->name ?? 'S', 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="font-heading font-bold text-xl text-stone-900">
                            {{ $student->user->name ?? 'Student Profile' }}
                        </h3>
                        <p class="text-xs text-stone-500">{{ $student->user->email ?? '' }}</p>
                    </div>
                </div>
            </x-slot>

            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm mt-4">
                <div class="border-b border-stone-100 pb-3">
                    <dt class="text-xs font-medium text-stone-400 uppercase tracking-wider">Department</dt>
                    <dd class="text-stone-800 font-medium mt-0.5">{{ $student->department ?? 'Not set' }}</dd>
                </div>
                <div class="border-b border-stone-100 pb-3">
                    <dt class="text-xs font-medium text-stone-400 uppercase tracking-wider">Batch</dt>
                    <dd class="text-stone-800 font-medium mt-0.5">{{ $student->batch ?? 'Not set' }}</dd>
                </div>
                <div class="border-b border-stone-100 pb-3">
                    <dt class="text-xs font-medium text-stone-400 uppercase tracking-wider">Academic Year</dt>
                    <dd class="text-stone-800 font-medium mt-0.5">{{ $student->academic_year ?? 'Not set' }}</dd>
                </div>
                <div class="border-b border-stone-100 pb-3">
                    <dt class="text-xs font-medium text-stone-400 uppercase tracking-wider">Role</dt>
                    <dd class="text-stone-800 font-medium mt-0.5">Student</dd>
                </div>
                <div class="col-span-full pt-1">
                    <dt class="text-xs font-medium text-stone-400 uppercase tracking-wider mb-1">Research Interests</dt>
                    <dd class="text-stone-700 leading-relaxed bg-stone-50/70 p-3.5 rounded-lg border border-stone-100">
                        {{ $student->research_interests ?? 'No research interests recorded yet.' }}
                    </dd>
                </div>
            </dl>
        </x-card>

        <!-- Feature 13: Skill Fingerprint (Unified Badge & Card Treatment) -->
        <x-card title="Skill Fingerprint" subtitle="Derived from verified course project contributions" class="mb-6">
            <div class="flex flex-wrap gap-2 pt-2">
                @forelse ($skillFingerprint as $skill => $count)
                    <x-badge variant="brand" size="md">
                        <span>{{ $skill }}</span>
                        <span class="ml-1.5 pl-1.5 border-l border-blue-200 text-blue-800 font-semibold">{{ $count }}</span>
                    </x-badge>
                @empty
                    <p class="text-stone-400 text-sm italic py-2">No verified skills yet — submit or contribute to a course project to start building your record.</p>
                @endforelse
            </div>
        </x-card>

        <!-- Danger Zone (Delete with Alpine Modal) -->
        <div class="flex items-center justify-between px-2 pt-2">
            <span class="text-xs text-stone-400">Permanently remove student profile</span>
            <x-delete-modal
                :action="route('students.destroy', $student)"
                title="Delete Student Profile"
                message="Are you sure you want to delete your student profile? This will remove your group links and project attributions."
                buttonText="Delete Profile"
            />
        </div>
    </x-container>
</x-app-layout>

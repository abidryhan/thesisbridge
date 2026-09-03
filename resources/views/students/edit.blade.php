<x-app-layout>
    <x-slot name="header">
        <h2 class="font-heading font-bold text-2xl text-stone-900 leading-tight">
            Edit Student Profile
        </h2>
    </x-slot>

    <x-container size="narrow">
        <x-card>
            <form method="POST" action="{{ route('students.update', $student) }}">
                @csrf
                @method('PUT')

                <div class="mb-5">
                    <label for="department" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Department</label>
                    <input type="text" name="department" id="department" value="{{ old('department', $student->department) }}"
                        class="w-full border-stone-200 rounded-lg px-3.5 py-2.5 text-sm focus:border-blue-600 focus:ring-blue-600">
                    @error('department')
                        <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                    <div>
                        <label for="batch" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Batch</label>
                        <input type="text" name="batch" id="batch" value="{{ old('batch', $student->batch) }}"
                            class="w-full border-stone-200 rounded-lg px-3.5 py-2.5 text-sm focus:border-blue-600 focus:ring-blue-600">
                        @error('batch')
                            <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="academic_year" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Academic Year</label>
                        <input type="number" name="academic_year" id="academic_year" value="{{ old('academic_year', $student->academic_year) }}"
                            class="w-full border-stone-200 rounded-lg px-3.5 py-2.5 text-sm focus:border-blue-600 focus:ring-blue-600">
                        @error('academic_year')
                            <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label for="research_interests" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Research Interests</label>
                    <textarea name="research_interests" id="research_interests" rows="4"
                        class="w-full border-stone-200 rounded-lg px-3.5 py-2.5 text-sm focus:border-blue-600 focus:ring-blue-600 leading-relaxed">{{ old('research_interests', $student->research_interests) }}</textarea>
                    @error('research_interests')
                        <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-stone-100">
                    <a href="{{ route('students.show', $student) }}" class="text-xs font-medium text-stone-500 hover:text-stone-700">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium text-xs px-5 py-2.5 rounded-lg shadow-xs transition">
                        Update Profile
                    </button>
                </div>
            </form>
        </x-card>
    </x-container>
</x-app-layout>

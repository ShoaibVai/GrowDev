<div class="form-item p-4 bg-gray-50 rounded-lg border border-gray-200">
    <div class="flex justify-between items-start mb-3">
        <h4 class="font-semibold text-gray-800">{{ __('Education') }} #{{ $index + 1 }}</h4>
        <button type="button" onclick="removeElement(this)" class="text-red-600 hover:text-red-800 text-sm font-medium">
            {{ __('Remove') }}
        </button>
    </div>

    <div class="grid grid-cols-2 gap-3">
        <!-- School Name -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('School / University') }} *</label>
            <input type="text" name="educations[{{ $index }}][school_name]" 
                value="{{ old("educations.$index.school_name", $edu->school_name ?? '') }}" 
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <!-- Degree -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Degree') }} *</label>
            <input type="text" name="educations[{{ $index }}][degree]" 
                value="{{ old("educations.$index.degree", $edu->degree ?? '') }}" 
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                placeholder="e.g., Bachelor, Master, Diploma">
        </div>

        <!-- Field of Study -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Field of Study') }} *</label>
            <input type="text" name="educations[{{ $index }}][field_of_study]" 
                value="{{ old("educations.$index.field_of_study", $edu->field_of_study ?? '') }}" 
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                placeholder="e.g., Computer Science">
        </div>

        <!-- Start Date -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Start Date') }} *</label>
            <input type="date" name="educations[{{ $index }}][start_date]" 
                value="{{ old("educations.$index.start_date", $edu?->start_date?->format('Y-m-d') ?? '') }}" 
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <!-- End Date -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('End Date') }} *</label>
            <input type="date" name="educations[{{ $index }}][end_date]" 
                value="{{ old("educations.$index.end_date", $edu?->end_date?->format('Y-m-d') ?? '') }}" 
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <!-- Description -->
        <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Description') }}</label>
            <textarea name="educations[{{ $index }}][description]" 
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                placeholder="Additional details about your education">{{ old("educations.$index.description", $edu->description ?? '') }}</textarea>
        </div>
    </div>
</div>

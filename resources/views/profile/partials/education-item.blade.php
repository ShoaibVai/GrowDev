<div class="form-item p-4 rounded-lg" style="background-color: var(--color-surface-2); border: 1px solid var(--color-border);">
    <div class="flex justify-between items-start mb-3">
        <h4 class="font-semibold text-sm" style="color: var(--color-text); font-family: var(--font-mono);">{{ __('Education') }} #{{ $index + 1 }}</h4>
        <button type="button" onclick="removeElement(this)" class="text-sm font-medium focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="color: var(--color-danger);">
            {{ __('Remove') }}
        </button>
    </div>

    <div class="grid grid-cols-2 gap-3">
        <!-- School Name -->
        <div>
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">{{ __('School / University') }} *</label>
            <input type="text" name="educations[{{ $index }}][school_name]" 
                value="{{ old("educations.$index.school_name", $edu->school_name ?? '') }}" 
                required
                class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
        </div>

        <!-- Degree -->
        <div>
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">{{ __('Degree') }} *</label>
            <input type="text" name="educations[{{ $index }}][degree]" 
                value="{{ old("educations.$index.degree", $edu->degree ?? '') }}" 
                required
                class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);"
                placeholder="e.g., Bachelor, Master, Diploma">
        </div>

        <!-- Field of Study -->
        <div>
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">{{ __('Field of Study') }} *</label>
            <input type="text" name="educations[{{ $index }}][field_of_study]" 
                value="{{ old("educations.$index.field_of_study", $edu->field_of_study ?? '') }}" 
                required
                class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);"
                placeholder="e.g., Computer Science">
        </div>

        <!-- Start Date -->
        <div>
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">{{ __('Start Date') }} *</label>
            <input type="date" name="educations[{{ $index }}][start_date]" 
                value="{{ old("educations.$index.start_date", $edu?->start_date?->format('Y-m-d') ?? '') }}" 
                required
                class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
        </div>

        <!-- End Date -->
        <div>
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">{{ __('End Date') }} *</label>
            <input type="date" name="educations[{{ $index }}][end_date]" 
                value="{{ old("educations.$index.end_date", $edu?->end_date?->format('Y-m-d') ?? '') }}" 
                required
                class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
        </div>

        <!-- Description -->
        <div class="col-span-2">
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">{{ __('Description') }}</label>
            <textarea name="educations[{{ $index }}][description]" 
                rows="3"
                class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);"
                placeholder="Additional details about your education">{{ old("educations.$index.description", $edu->description ?? '') }}</textarea>
        </div>
    </div>
</div>

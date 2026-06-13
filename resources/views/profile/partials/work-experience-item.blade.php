<div class="form-item p-4 rounded-lg" style="background-color: var(--color-surface-2); border: 1px solid var(--color-border);">
    <div class="flex justify-between items-start mb-3">
        <h4 class="font-semibold text-sm" style="color: var(--color-text); font-family: var(--font-mono);">{{ __('Position') }} #{{ $index + 1 }}</h4>
        <button type="button" onclick="removeElement(this)" class="text-sm font-medium focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="color: var(--color-danger);">
            {{ __('Remove') }}
        </button>
    </div>

    <div class="grid grid-cols-2 gap-3">
        <!-- Job Title -->
        <div>
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">{{ __('Job Title') }} *</label>
            <input type="text" name="work_experiences[{{ $index }}][job_title]" 
                value="{{ old("work_experiences.$index.job_title", $exp->job_title ?? '') }}" 
                required
                class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
        </div>

        <!-- Company Name -->
        <div>
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">{{ __('Company Name') }} *</label>
            <input type="text" name="work_experiences[{{ $index }}][company_name]" 
                value="{{ old("work_experiences.$index.company_name", $exp->company_name ?? '') }}" 
                required
                class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
        </div>

        <!-- Start Date -->
        <div>
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">{{ __('Start Date') }} *</label>
            <input type="date" name="work_experiences[{{ $index }}][start_date]" 
                value="{{ old("work_experiences.$index.start_date", $exp?->start_date?->format('Y-m-d') ?? '') }}" 
                required
                class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
        </div>

        <!-- End Date -->
        <div>
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">{{ __('End Date') }}</label>
            <input type="date" name="work_experiences[{{ $index }}][end_date]" 
                value="{{ old("work_experiences.$index.end_date", $exp?->end_date?->format('Y-m-d') ?? '') }}" 
                class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
        </div>

        <!-- Currently Working -->
        <div class="col-span-2">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="work_experiences[{{ $index }}][currently_working]" value="1" 
                    {{ old("work_experiences.$index.currently_working", $exp->currently_working ?? false) ? 'checked' : '' }}
                    class="rounded focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                    style="accent-color: var(--color-accent);">
                <span class="text-sm font-medium" style="color: var(--color-text);">{{ __('I currently work here') }}</span>
            </label>
        </div>

        <!-- Description -->
        <div class="col-span-2">
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">{{ __('Description') }}</label>
            <textarea name="work_experiences[{{ $index }}][description]" 
                rows="3"
                class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">{{ old("work_experiences.$index.description", $exp->description ?? '') }}</textarea>
        </div>
    </div>
</div>

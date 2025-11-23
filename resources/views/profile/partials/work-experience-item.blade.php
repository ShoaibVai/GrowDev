<div class="form-item p-4 bg-gray-50 rounded-lg border border-gray-200">
    <div class="flex justify-between items-start mb-3">
        <h4 class="font-semibold text-gray-800">{{ __('Position') }} #{{ $index + 1 }}</h4>
        <button type="button" onclick="removeElement(this)" class="text-red-600 hover:text-red-800 text-sm font-medium">
            {{ __('Remove') }}
        </button>
    </div>

    <div class="grid grid-cols-2 gap-3">
        <!-- Job Title -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Job Title') }} *</label>
            <input type="text" name="work_experiences[{{ $index }}][job_title]" 
                value="{{ old("work_experiences.$index.job_title", $exp->job_title ?? '') }}" 
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <!-- Company Name -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Company Name') }} *</label>
            <input type="text" name="work_experiences[{{ $index }}][company_name]" 
                value="{{ old("work_experiences.$index.company_name", $exp->company_name ?? '') }}" 
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <!-- Start Date -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Start Date') }} *</label>
            <input type="date" name="work_experiences[{{ $index }}][start_date]" 
                value="{{ old("work_experiences.$index.start_date", $exp?->start_date?->format('Y-m-d') ?? '') }}" 
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <!-- End Date -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('End Date') }}</label>
            <input type="date" name="work_experiences[{{ $index }}][end_date]" 
                value="{{ old("work_experiences.$index.end_date", $exp?->end_date?->format('Y-m-d') ?? '') }}" 
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <!-- Currently Working -->
        <div class="col-span-2">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="work_experiences[{{ $index }}][currently_working]" value="1" 
                    {{ old("work_experiences.$index.currently_working", $exp->currently_working ?? false) ? 'checked' : '' }}
                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                <span class="text-sm font-medium text-gray-700">{{ __('I currently work here') }}</span>
            </label>
        </div>

        <!-- Description -->
        <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Description') }}</label>
            <textarea name="work_experiences[{{ $index }}][description]" 
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old("work_experiences.$index.description", $exp->description ?? '') }}</textarea>
        </div>
    </div>
</div>

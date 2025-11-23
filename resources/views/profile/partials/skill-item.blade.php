<div class="form-item p-3 bg-gray-50 rounded-lg border border-gray-200 flex items-end gap-3">
    <!-- Skill Name -->
    <div class="flex-1">
        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Skill Name') }} *</label>
        <input type="text" name="skills[{{ $index }}][skill_name]" 
            value="{{ old("skills.$index.skill_name", $skill->skill_name ?? '') }}" 
            required
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
            placeholder="e.g., JavaScript, Laravel, React">
    </div>

    <!-- Proficiency Level -->
    <div class="flex-1">
        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Proficiency') }} *</label>
        <select name="skills[{{ $index }}][proficiency]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">{{ __('Select level') }}</option>
            <option value="beginner" {{ old("skills.$index.proficiency", $skill->proficiency ?? '') === 'beginner' ? 'selected' : '' }}>{{ __('Beginner') }}</option>
            <option value="intermediate" {{ old("skills.$index.proficiency", $skill->proficiency ?? '') === 'intermediate' ? 'selected' : '' }}>{{ __('Intermediate') }}</option>
            <option value="advanced" {{ old("skills.$index.proficiency", $skill->proficiency ?? '') === 'advanced' ? 'selected' : '' }}>{{ __('Advanced') }}</option>
            <option value="expert" {{ old("skills.$index.proficiency", $skill->proficiency ?? '') === 'expert' ? 'selected' : '' }}>{{ __('Expert') }}</option>
        </select>
    </div>

    <!-- Remove Button -->
    <div>
        <button type="button" onclick="removeElement(this)" class="px-3 py-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
</div>

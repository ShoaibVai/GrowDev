<div class="form-item p-4 bg-gray-50 rounded-lg border border-gray-200">
    <div class="flex justify-between items-start mb-3">
        <h4 class="font-semibold text-gray-800">{{ __('Certification') }} #{{ $index + 1 }}</h4>
        <button type="button" onclick="removeElement(this)" class="text-red-600 hover:text-red-800 text-sm font-medium">
            {{ __('Remove') }}
        </button>
    </div>

    <div class="grid grid-cols-2 gap-3">
        <!-- Certification Name -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Certification Name') }} *</label>
            <input type="text" name="certifications[{{ $index }}][certification_name]" 
                value="{{ old("certifications.$index.certification_name", $cert->certification_name ?? '') }}" 
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <!-- Issuer -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Issuer') }} *</label>
            <input type="text" name="certifications[{{ $index }}][issuer]" 
                value="{{ old("certifications.$index.issuer", $cert->issuer ?? '') }}" 
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <!-- Issue Date -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Issue Date') }} *</label>
            <input type="date" name="certifications[{{ $index }}][issue_date]" 
                value="{{ old("certifications.$index.issue_date", $cert?->issue_date?->format('Y-m-d') ?? '') }}" 
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <!-- Expiry Date -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Expiry Date') }}</label>
            <input type="date" name="certifications[{{ $index }}][expiry_date]" 
                value="{{ old("certifications.$index.expiry_date", $cert?->expiry_date?->format('Y-m-d') ?? '') }}" 
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <!-- Credential URL -->
        <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Credential URL') }}</label>
            <input type="url" name="certifications[{{ $index }}][credential_url]" 
                value="{{ old("certifications.$index.credential_url", $cert->credential_url ?? '') }}" 
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                placeholder="https://...">
        </div>

        <!-- Description -->
        <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Description') }}</label>
            <textarea name="certifications[{{ $index }}][description]" 
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                placeholder="Additional details about this certification">{{ old("certifications.$index.description", $cert->description ?? '') }}</textarea>
        </div>
    </div>
</div>

<div class="form-item p-4 rounded-lg" style="background-color: var(--color-surface-2); border: 1px solid var(--color-border);">
    <div class="flex justify-between items-start mb-3">
        <h4 class="font-semibold text-sm" style="color: var(--color-text); font-family: var(--font-mono);">{{ __('Certification') }} #{{ $index + 1 }}</h4>
        <button type="button" onclick="removeElement(this)" class="text-sm font-medium focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="color: var(--color-danger);">
            {{ __('Remove') }}
        </button>
    </div>

    <div class="grid grid-cols-2 gap-3">
        <!-- Certification Name -->
        <div>
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">{{ __('Certification Name') }} *</label>
            <input type="text" name="certifications[{{ $index }}][certification_name]" 
                value="{{ old("certifications.$index.certification_name", $cert->certification_name ?? '') }}" 
                required
                class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
        </div>

        <!-- Issuer -->
        <div>
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">{{ __('Issuer') }} *</label>
            <input type="text" name="certifications[{{ $index }}][issuer]" 
                value="{{ old("certifications.$index.issuer", $cert->issuer ?? '') }}" 
                required
                class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
        </div>

        <!-- Issue Date -->
        <div>
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">{{ __('Issue Date') }} *</label>
            <input type="date" name="certifications[{{ $index }}][issue_date]" 
                value="{{ old("certifications.$index.issue_date", $cert?->issue_date?->format('Y-m-d') ?? '') }}" 
                required
                class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
        </div>

        <!-- Expiry Date -->
        <div>
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">{{ __('Expiry Date') }}</label>
            <input type="date" name="certifications[{{ $index }}][expiry_date]" 
                value="{{ old("certifications.$index.expiry_date", $cert?->expiry_date?->format('Y-m-d') ?? '') }}" 
                class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);">
        </div>

        <!-- Credential URL -->
        <div class="col-span-2">
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">{{ __('Credential URL') }}</label>
            <input type="url" name="certifications[{{ $index }}][credential_url]" 
                value="{{ old("certifications.$index.credential_url", $cert->credential_url ?? '') }}" 
                class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);"
                placeholder="https://...">
        </div>

        <!-- Description -->
        <div class="col-span-2">
            <label class="block text-sm font-medium mb-1" style="color: var(--color-text);">{{ __('Description') }}</label>
            <textarea name="certifications[{{ $index }}][description]" 
                rows="3"
                class="w-full px-3 py-2 rounded-lg focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                style="background-color: var(--color-surface-3); border: 1px solid var(--color-border); color: var(--color-text);"
                placeholder="Additional details about this certification">{{ old("certifications.$index.description", $cert->description ?? '') }}</textarea>
        </div>
    </div>
</div>

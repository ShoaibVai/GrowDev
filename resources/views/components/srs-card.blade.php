{{-- Merge class for border-left color --}}
@php
    $borderLeftColor = 'var(--color-accent)';
    $mergedClass = $attributes->get('class', '');
@endphp

<div {{ $attributes->merge(['class' => 'rounded-lg shadow-md hover:shadow-lg transition']) }}
     style="background-color: var(--color-surface); padding: 1.5rem; border-left: 4px solid {{ $borderLeftColor }};">
    <h3 style="font-family: var(--font-mono); font-size: 1.25rem; font-weight: 700; color: var(--color-text); margin-bottom: 0.5rem;">{{ $srs->title }}</h3>
    <p style="color: var(--color-text-muted); font-size: 0.875rem; margin-bottom: 1rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $srs->description }}</p>

    <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.75rem; color: var(--color-text-muted); margin-bottom: 1rem;">
        <span>{{ $srs->functionalRequirements->count() }} Requirements</span>
        <span>{{ $srs->created_at->format('M d, Y') }}</span>
    </div>

    <div class="flex gap-3">
        <a href="{{ route('documentation.srs.edit', $srs) }}" 
           class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
           style="flex: 1; padding: 0.5rem 0.75rem; background-color: var(--color-accent); color: white; font-size: 0.875rem; border-radius: 0.25rem; text-align: center; transition: background-color 0.2s;"
           onmouseover="this.style.backgroundColor='color-mix(in srgb, var(--color-accent) 80%, black)'"
           onmouseout="this.style.backgroundColor='var(--color-accent)'"
           aria-label="Edit {{ $srs->title }}" title="Edit {{ $srs->title }}">
            ✏️ Edit
        </a>
        <a href="{{ route('documentation.srs.pdf', $srs) }}" 
           class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
           style="flex: 1; padding: 0.5rem 0.75rem; background-color: var(--color-success); color: white; font-size: 0.875rem; border-radius: 0.25rem; text-align: center; transition: background-color 0.2s;"
           onmouseover="this.style.backgroundColor='color-mix(in srgb, var(--color-success) 80%, black)'"
           onmouseout="this.style.backgroundColor='var(--color-success)'"
           aria-label="Download PDF for {{ $srs->title }}" title="Download PDF for {{ $srs->title }}">
            📥 PDF
        </a>
        <form method="POST" action="{{ route('documentation.srs.destroy', $srs) }}" style="flex: 1;" aria-label="Delete {{ $srs->title }}">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Are you sure?')" 
                    class="focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none"
                    style="width: 100%; padding: 0.5rem 0.75rem; background-color: var(--color-danger); color: white; font-size: 0.875rem; border-radius: 0.25rem; transition: background-color 0.2s;"
                    onmouseover="this.style.backgroundColor='color-mix(in srgb, var(--color-danger) 80%, black)'"
                    onmouseout="this.style.backgroundColor='var(--color-danger)'">
                🗑️ Delete
            </button>
        </form>
    </div>
</div>

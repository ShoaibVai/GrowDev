@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none rounded-md shadow-sm transition-all duration-200']) }}
       style="border: 1px solid var(--color-border); background-color: var(--color-surface); color: var(--color-text);">

<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:ring-offset-2 transition-all duration-200']) }}
        style="background-color: var(--color-accent);"
        onmouseover="this.style.backgroundColor='color-mix(in srgb, var(--color-accent) 80%, black)'"
        onmouseout="this.style.backgroundColor='var(--color-accent)'">
    {{ $slot }}
</button>

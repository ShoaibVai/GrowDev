<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-indigo-700 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:from-indigo-700 hover:to-indigo-800 focus:from-indigo-700 focus:to-indigo-800 active:from-indigo-900 active:to-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg']) }} data-ripple>
    {{ $slot }}
</button>

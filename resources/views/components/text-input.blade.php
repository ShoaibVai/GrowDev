@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition-all duration-300 hover:border-indigo-300 focus:scale-[1.01]']) }}>

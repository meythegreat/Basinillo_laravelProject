@props(['class' => 'h-8 w-8'])

<svg
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 24 24"
    fill="none"
    stroke-width="1.8"
    class="{{ $class }}"
>
    <defs>
        <linearGradient id="musicGradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#a855f7" />   {{-- Purple --}}
            <stop offset="50%" stop-color="#6366f1" />  {{-- Indigo --}}
            <stop offset="100%" stop-color="#ec4899" /> {{-- Pink --}}
        </linearGradient>
    </defs>

    {{-- Music Note Icon --}}
    <path
        d="M9 18V6l10-2v12"
        stroke="url(#musicGradient)"
        stroke-linecap="round"
        stroke-linejoin="round"
    />

    {{-- Left Circle (bass beat) --}}
    <circle
        cx="6"
        cy="18"
        r="3"
        stroke="url(#musicGradient)"
        stroke-width="1.8"
    />

    {{-- Right Circle (treble beat) --}}
    <circle
        cx="18"
        cy="16"
        r="3"
        stroke="url(#musicGradient)"
        stroke-width="1.8"
    />
</svg>
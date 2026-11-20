@switch($slug)
    @case('aksesoris')
        <svg viewBox="0 0 64 64" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <rect x="10" y="24" width="44" height="28" rx="5" />
            <path d="M22 24v-4a10 10 0 0 1 20 0v4" />
            <path d="M10 36c8 5 36 5 44 0" />
            <circle class="accent" cx="32" cy="39" r="3" />
        </svg>
    @break

    @case('buku')
        <svg viewBox="0 0 64 64" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"
            aria-hidden="true">
            <rect x="16" y="9" width="34" height="46" rx="4" />
            <path d="M24 9v46" />
            <path d="M30 28h12M30 35h8" />
            <path class="accent" d="M40 9v12l3-2.5 3 2.5V9" />
        </svg>
    @break

    @case('elektronik')
        <svg viewBox="0 0 64 64" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"
            aria-hidden="true">
            <path d="M14 38v-6a18 18 0 0 1 36 0v6" />
            <rect x="10" y="36" width="9" height="16" rx="4.5" />
            <rect x="45" y="36" width="9" height="16" rx="4.5" />
            <path class="accent" d="M14.5 42v4" />
        </svg>
    @break

    @case('fashion')
        <svg viewBox="0 0 64 64" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"
            aria-hidden="true">
            <path d="M22 10 8 18l6 10 6-3v27h24V25l6 3 6-10-14-8c-2 4-6 6-10 6s-8-2-10-6z" />
            <path class="accent" d="M20 47h24" />
        </svg>
    @break

    @case('olahraga')
        <svg viewBox="0 0 64 64" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"
            aria-hidden="true">
            <ellipse cx="32" cy="16" rx="13" ry="5" />
            <path d="M19 16v32c0 3 6 6 13 6s13-3 13-6V16" />
            <path class="accent" d="M19 30c0 3 6 6 13 6s13-3 13-6M19 40c0 3 6 6 13 6s13-3 13-6" />
        </svg>
    @break

    @case('rumah-tangga')
        <svg viewBox="0 0 64 64" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"
            aria-hidden="true">
            <path d="M14 34a18 18 0 0 1 36 0z" />
            <path d="M32 34v18M20 54h24" />
            <path class="accent" d="M24 42h-3M43 42h-3" />
        </svg>
    @break

    @default
        <span class="p-letter">{{ mb_strtoupper(mb_substr($name, 0, 1)) }}</span>
@endswitch

@switch($icon)
    @case('pill')
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M4.5 12.5a5 5 0 0 1 7.07-7.07l4.95 4.95a5 5 0 0 1-7.07 7.07L4.5 12.5Z"/>
            <path d="M9.5 9.5 14.5 14.5"/>
        </svg>
        @break
    @case('sparkles')
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 3l1.5 4.5L18 9l-4.5 1.5L12 15l-1.5-4.5L6 9l4.5-1.5L12 3z"/>
        </svg>
        @break
    @case('baby')
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="11" r="3"/>
            <path d="M5 21c1.5-4 4.5-6 7-6s5.5 2 7 6"/>
        </svg>
        @break
    @case('leaf')
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M6 21C4 15 8 12 12 8s6-4 8-4c-1 4-5 7-6 11-1 4-4 5-8 4Z"/>
        </svg>
        @break
    @case('flask')
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M7 3h10"/>
            <path d="M14 3v5a5 5 0 0 1-4 4 5 5 0 0 1-4-4V3"/>
            <path d="M5 12h14l-3 8H8l-3-8Z"/>
        </svg>
        @break
    @case('heart-pulse')
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M4 12h4l2-5 3 10 2-5h3"/>
            <path d="M20 6v12"/>
        </svg>
        @break
    @case('first-aid')
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
            <path d="M12 7v10M7 12h10"/>
        </svg>
        @break
    @default
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
        </svg>
@endswitch

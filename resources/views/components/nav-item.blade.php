@props(['active' => false, 'href' => '#'])

<li>
    <a href="{{ $href }}" @class([
        'nav-link-tactical group',
        'active' => $active
    ])>
        <span @class([
            'w-1 h-1 bg-current transition-all duration-300',
            'bg-accent scale-150 rotate-45 shadow-[0_0_10px_#FF4655]' => $active,
            'opacity-30 group-hover:opacity-100 group-hover:bg-accent' => !$active
        ])></span>
        <span class="relative">
            {{ $slot }}
            @if($active)
                <span class="absolute -bottom-1 left-0 w-full h-[1px] bg-accent/30"></span>
            @endif
        </span>
    </a>
</li>

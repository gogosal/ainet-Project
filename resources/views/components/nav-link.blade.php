<a href="{{ $route }}"
    class="flex items-center gap-[.6rem] px-[.7rem] py-[.42rem] rounded-[1px] no-underline text-[.75rem] tracking-[.02em] border mb-[2px] transition-all duration-150 {{ $linkClasses }}">

    <svg class="w-[14px] h-[14px] shrink-0 {{ $iconClasses }}" fill="none" viewBox="0 0 24 24" stroke-width="2"
        stroke-linecap="round" stroke-linejoin="round">
        {!! $icon !!}
    </svg>
    {{ $label }}
</a>

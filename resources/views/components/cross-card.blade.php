@props(['cross'])

@php
    $motherImage = $cross->mother?->main_image_url ?? $cross->mother?->primaryImage?->image_url;
@endphp

<a href="{{ route('crosses.show', $cross) }}" class="block bg-white rounded-xl shadow-sm border border-stone-200 hover:shadow-md hover:border-green-300 transition group overflow-hidden">
    <div class="aspect-square bg-stone-100 overflow-hidden" x-data="{ failed: false }">
        @if($motherImage)
            <img x-show="!failed" x-on:error="failed = true"
                src="{{ $motherImage }}" alt="{{ $cross->mother?->name }}"
                class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                loading="lazy">
        @endif
        <div x-show="{{ $motherImage ? 'failed' : 'true' }}" class="flex items-center justify-center w-full h-full text-stone-300">
            <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C9.5 2 8 4.5 8 7c0 1.5.5 3 1.5 4.2C8.5 12.5 7 14.5 7 17c0 3 2.5 5 5 5s5-2 5-5c0-2.5-1.5-4.5-2.5-5.8C15.5 10 16 8.5 16 7c0-2.5-1.5-5-4-5z"/></svg>
        </div>
    </div>

    <div class="p-5">
        <div class="flex items-start justify-between gap-2 mb-3">
            <span class="text-xs font-mono text-stone-400">{{ $cross->code }}</span>
            <x-scarcity-badge :level="$cross->scarcity_level" :count="$cross->seed_count" />
        </div>

        <h3 class="font-semibold text-stone-900 group-hover:text-green-700 transition mb-1">
            {{ $cross->display_name }}
        </h3>

        <div class="flex items-center justify-between mt-3">
            <span class="text-lg font-bold text-green-700">${{ number_format($cross->price, 2) }}</span>

            @if($cross->status === 'available')
                <span class="text-xs text-stone-500">
                    {{ ucfirst($cross->seed_count_accuracy) }} count
                </span>
            @endif
        </div>

        @if($cross->mother?->species !== $cross->father?->species && $cross->father)
            <div class="mt-2 text-xs text-purple-600 font-medium">
                Interspecies cross
            </div>
        @endif

        @if($cross->is_op)
            <div class="mt-2 text-xs text-blue-600 font-medium">Open pollinated</div>
        @endif
    </div>
</a>

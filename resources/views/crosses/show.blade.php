<x-layouts.app :title="$cross->display_name">
    {{-- Breadcrumb --}}
    <nav class="text-sm text-stone-500 mb-6">
        <a href="{{ route('crosses.index') }}" class="hover:text-green-700">Seed Crosses</a>
        <span class="mx-2">/</span>
        <span class="text-stone-900">{{ $cross->code }}</span>
    </nav>

    {{-- Cross header --}}
    <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6 sm:p-8 mb-8">
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-6">
            <div>
                <span class="text-sm font-mono text-stone-400">{{ $cross->code }}</span>
                <h1 class="text-3xl font-bold text-stone-900 mt-1">{{ $cross->display_name }}</h1>
                @if($cross->is_op)
                    <span class="inline-block mt-2 text-xs bg-blue-100 text-blue-700 px-2.5 py-1 rounded-full font-medium">Open Pollinated</span>
                @endif
                @if($cross->is_f2)
                    <span class="inline-block mt-2 text-xs bg-amber-100 text-amber-700 px-2.5 py-1 rounded-full font-medium">F2 Generation</span>
                @endif
            </div>
            <x-scarcity-badge :level="$cross->scarcity_level" :count="$cross->seed_count" class="text-sm" />
        </div>

        {{-- Price & Seed info --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
            <div>
                <h2 class="text-sm font-medium text-stone-500 uppercase tracking-wider mb-2">Price</h2>
                <span class="text-3xl font-bold text-green-700">${{ number_format($cross->price, 2) }}</span>
                @if($cross->quantity_unit)
                    <span class="text-sm text-stone-500 ml-1">/ {{ $cross->quantity_unit }}</span>
                @endif
            </div>

            <div>
                <h2 class="text-sm font-medium text-stone-500 uppercase tracking-wider mb-2">Seed Count</h2>
                <div class="flex items-center gap-2">
                    <span class="text-lg font-semibold">
                        @if($cross->seed_count > 0)
                            ~{{ number_format($cross->seed_count) }} seeds
                        @elseif($cross->status === 'coming_soon')
                            Not yet available
                        @else
                            Out of stock
                        @endif
                    </span>
                    <span class="text-sm text-stone-400">({{ ucfirst($cross->seed_count_accuracy) }})</span>
                </div>
            </div>

            <div>
                <h2 class="text-sm font-medium text-stone-500 uppercase tracking-wider mb-2">Status</h2>
                <span class="text-lg font-semibold capitalize">{{ str_replace('_', ' ', $cross->status) }}</span>
            </div>
        </div>

        @if($cross->description)
            <div>
                <h2 class="text-sm font-medium text-stone-500 uppercase tracking-wider mb-2">Description</h2>
                <p class="text-stone-700">{{ $cross->description }}</p>
            </div>
        @endif
    </div>

    {{-- Parent Clones - separate sections --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        {{-- Mother Clone --}}
        <section class="bg-white rounded-xl shadow-sm border border-stone-200 overflow-hidden">
            <div class="bg-pink-50 border-b border-pink-100 px-6 py-3">
                <h2 class="text-sm font-semibold text-pink-800 uppercase tracking-wider">Mother Clone</h2>
            </div>

            @if($cross->mother)
                @php
                    $motherImages = $cross->mother->images->pluck('image_url')->toArray();
                    if (empty($motherImages) && $cross->mother->main_image_url) {
                        $motherImages = [$cross->mother->main_image_url];
                    }
                @endphp

                @if(!empty($motherImages))
                    <div x-data="{ current: 0, images: {{ Js::from($motherImages) }}, failed: {} }" class="bg-stone-100">
                        <div class="aspect-square overflow-hidden relative">
                            <template x-for="(img, index) in images" :key="index">
                                <img x-show="current === index"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0"
                                    x-transition:enter-end="opacity-100"
                                    :src="img" :alt="'{{ $cross->mother->name }}'"
                                    class="absolute inset-0 w-full h-full object-cover"
                                    x-on:error="failed[index] = true">
                            </template>
                        </div>
                        <template x-if="images.length > 1">
                            <div class="flex gap-2 p-3 overflow-x-auto bg-stone-50">
                                <template x-for="(img, index) in images" :key="'mt-' + index">
                                    <button @click="current = index"
                                        class="flex-shrink-0 w-14 h-14 rounded-lg overflow-hidden border-2 transition"
                                        :class="current === index ? 'border-pink-500 ring-1 ring-pink-500' : 'border-transparent hover:border-stone-300'">
                                        <img :src="img" :alt="'{{ $cross->mother->name }}'" class="w-full h-full object-cover"
                                            x-on:error="$el.parentElement.style.display='none'">
                                    </button>
                                </template>
                            </div>
                        </template>
                    </div>
                @endif

                <div class="p-6">
                    <a href="{{ route('clones.show', $cross->mother) }}" class="block hover:text-green-700 transition">
                        <h3 class="text-xl font-bold text-stone-900">{{ $cross->mother->name }}</h3>
                        <p class="text-sm text-stone-500 italic mt-1">{{ $cross->mother->species }}</p>
                    </a>
                </div>
            @else
                <div class="p-6">
                    <p class="text-stone-500">{{ $cross->mother_name_text ?? 'Unknown' }}</p>
                </div>
            @endif
        </section>

        {{-- Father Clone --}}
        <section class="bg-white rounded-xl shadow-sm border border-stone-200 overflow-hidden">
            <div class="bg-blue-50 border-b border-blue-100 px-6 py-3">
                <h2 class="text-sm font-semibold text-blue-800 uppercase tracking-wider">Father Clone</h2>
            </div>

            @if($cross->father)
                @php
                    $fatherImages = $cross->father->images->pluck('image_url')->toArray();
                    if (empty($fatherImages) && $cross->father->main_image_url) {
                        $fatherImages = [$cross->father->main_image_url];
                    }
                @endphp

                @if(!empty($fatherImages))
                    <div x-data="{ current: 0, images: {{ Js::from($fatherImages) }}, failed: {} }" class="bg-stone-100">
                        <div class="aspect-square overflow-hidden relative">
                            <template x-for="(img, index) in images" :key="index">
                                <img x-show="current === index"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0"
                                    x-transition:enter-end="opacity-100"
                                    :src="img" :alt="'{{ $cross->father->name }}'"
                                    class="absolute inset-0 w-full h-full object-cover"
                                    x-on:error="failed[index] = true">
                            </template>
                        </div>
                        <template x-if="images.length > 1">
                            <div class="flex gap-2 p-3 overflow-x-auto bg-stone-50">
                                <template x-for="(img, index) in images" :key="'ft-' + index">
                                    <button @click="current = index"
                                        class="flex-shrink-0 w-14 h-14 rounded-lg overflow-hidden border-2 transition"
                                        :class="current === index ? 'border-blue-500 ring-1 ring-blue-500' : 'border-transparent hover:border-stone-300'">
                                        <img :src="img" :alt="'{{ $cross->father->name }}'" class="w-full h-full object-cover"
                                            x-on:error="$el.parentElement.style.display='none'">
                                    </button>
                                </template>
                            </div>
                        </template>
                    </div>
                @endif

                <div class="p-6">
                    <a href="{{ route('clones.show', $cross->father) }}" class="block hover:text-green-700 transition">
                        <h3 class="text-xl font-bold text-stone-900">{{ $cross->father->name }}</h3>
                        <p class="text-sm text-stone-500 italic mt-1">{{ $cross->father->species }}</p>
                    </a>
                </div>
            @else
                <div class="p-6">
                    <p class="text-stone-500 italic">
                        @if($cross->is_op)
                            Open pollinated &mdash; seeds produced naturally without a specific father clone.
                        @else
                            {{ $cross->father_name_text ?? 'Unknown' }}
                        @endif
                    </p>
                </div>
            @endif
        </section>
    </div>

    @if($cross->mother && $cross->father && $cross->mother->species !== $cross->father->species)
        <div class="flex items-center gap-2 text-sm text-purple-600 bg-purple-50 rounded-lg px-4 py-3 mb-8">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.715-5.349L10 6.017l-3.762 1.505 1.715 5.349a1 1 0 01-.285 1.05A3.989 3.989 0 015 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.616a1 1 0 01.894-1.79l1.599.8L9 4.323V3a1 1 0 011-1z"/></svg>
            Interspecies hybrid &mdash; {{ $cross->mother->species }} &times; {{ $cross->father->species }}
        </div>
    @endif

    {{-- Related Crosses --}}
    @if($relatedCrosses->isNotEmpty())
        <section>
            <h2 class="text-2xl font-bold text-stone-900 mb-6">Related Crosses</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($relatedCrosses as $related)
                    <x-cross-card :cross="$related" />
                @endforeach
            </div>
        </section>
    @endif
</x-layouts.app>

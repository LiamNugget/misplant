<x-layouts.app :title="$clone->name">
    {{-- Breadcrumb --}}
    <nav class="text-sm text-stone-500 mb-6">
        <a href="{{ route('clones.index') }}" class="hover:text-green-700">Parent Clones</a>
        <span class="mx-2">/</span>
        <span class="text-stone-900">{{ $clone->name }}</span>
    </nav>

    <div class="bg-white rounded-xl shadow-sm border border-stone-200 overflow-hidden mb-8">
        @php
            $allImages = $clone->images;
            $mainImageUrl = $clone->main_image_url ?? $allImages->first()?->image_url;
        @endphp

        @if($mainImageUrl || $allImages->isNotEmpty())
            <div x-data="{
                images: {{ Js::from($allImages->count() > 0 ? $allImages->pluck('image_url')->toArray() : ($mainImageUrl ? [$mainImageUrl] : [])) }},
                current: 0,
                failed: {},
                next() { this.current = (this.current + 1) % this.images.length },
                prev() { this.current = (this.current - 1 + this.images.length) % this.images.length }
            }">
                {{-- Main image --}}
                <div class="relative bg-stone-100 aspect-square overflow-hidden">
                    <template x-for="(img, index) in images" :key="index">
                        <img x-show="current === index"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            :src="img"
                            :alt="'{{ $clone->name }}'"
                            class="absolute inset-0 w-full h-full object-contain bg-stone-100"
                            x-on:error="failed[index] = true">
                    </template>

                    {{-- Prev/Next arrows --}}
                    <template x-if="images.length > 1">
                        <div>
                            <button @click="prev()" class="absolute left-3 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/60 text-white rounded-full w-10 h-10 flex items-center justify-center transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <button @click="next()" class="absolute right-3 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/60 text-white rounded-full w-10 h-10 flex items-center justify-center transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>

                            {{-- Counter --}}
                            <div class="absolute bottom-3 right-3 bg-black/50 text-white text-xs px-2 py-1 rounded-full">
                                <span x-text="current + 1"></span> / <span x-text="images.length"></span>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Thumbnail strip --}}
                @if($allImages->count() > 1)
                    <div class="flex gap-2 p-3 overflow-x-auto bg-stone-50">
                        <template x-for="(img, index) in images" :key="'thumb-' + index">
                            <button @click="current = index"
                                class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 transition"
                                :class="current === index ? 'border-green-600 ring-1 ring-green-600' : 'border-transparent hover:border-stone-300'">
                                <img :src="img" :alt="'{{ $clone->name }}'" class="w-full h-full object-cover"
                                    x-on:error="$el.parentElement.style.display='none'">
                            </button>
                        </template>
                    </div>
                @endif
            </div>
        @endif

        <div class="p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-4">
                <div>
                    <h1 class="text-3xl font-bold text-stone-900">{{ $clone->name }}</h1>
                    <p class="text-stone-500 italic mt-1">{{ $clone->species }}</p>
                </div>
                <div class="flex items-center gap-2">
                    @if($clone->is_monstrose)
                        <span class="text-xs bg-purple-100 text-purple-700 px-2.5 py-1 rounded-full font-medium">Monstrose</span>
                    @endif
                    <span class="text-sm bg-green-100 text-green-800 px-3 py-1 rounded-full font-medium">
                        {{ $crosses->count() }} crosses
                    </span>
                </div>
            </div>

            @if($clone->description)
                <p class="text-stone-700 leading-relaxed">{{ $clone->description }}</p>
            @endif

            @if($clone->tags->isNotEmpty())
                <div class="flex flex-wrap gap-2 mt-4">
                    @foreach($clone->tags as $tag)
                        <span class="text-xs font-medium px-2.5 py-1 rounded-full" style="background-color: {{ $tag->color }}20; color: {{ $tag->color }};">
                            {{ $tag->name }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Crosses involving this clone --}}
    <section>
        <h2 class="text-2xl font-bold text-stone-900 mb-6">Crosses</h2>

        @if($crosses->isEmpty())
            <p class="text-stone-500">No crosses currently listed for this clone.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($crosses as $cross)
                    <x-cross-card :cross="$cross" />
                @endforeach
            </div>
        @endif
    </section>
</x-layouts.app>

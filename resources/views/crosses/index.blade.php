<x-layouts.app title="Seed Crosses">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <h1 class="text-3xl font-bold text-stone-900">Seed Crosses</h1>
        <span class="text-stone-500">{{ $crosses->total() }} results</span>
    </div>

    {{-- Sticky Filters --}}
    <div x-data="{
            filtersOpen: false,
            compact: false,
            init() {
                const sentinel = this.$refs.sentinel;
                const observer = new IntersectionObserver(
                    ([e]) => { this.compact = !e.isIntersecting; },
                    { rootMargin: '-64px 0px 0px 0px', threshold: 0 }
                );
                observer.observe(sentinel);
            }
        }">
        {{-- Sentinel element: when this scrolls out of view, search bar goes compact --}}
        <div x-ref="sentinel" class="h-0"></div>

        <div class="sticky top-16 z-40 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 transition-all duration-200 mb-8"
            :class="compact ? 'bg-stone-50/95 backdrop-blur-sm shadow-sm py-2 border-b border-stone-200' : 'py-0'">
            <form action="{{ route('crosses.index') }}" method="GET" class="space-y-2" :class="compact ? 'space-y-1.5' : 'space-y-4'">
                {{-- Search bar --}}
                <div class="flex gap-2">
                    <div class="flex-1 relative">
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                            placeholder="Search by clone name or code..."
                            class="w-full pl-9 pr-4 rounded-lg border border-stone-300 focus:border-green-500 focus:ring-1 focus:ring-green-500 outline-none transition-all duration-200"
                            :class="compact ? 'py-1.5 text-sm' : 'py-2.5'">
                        <svg class="w-4 h-4 text-stone-400 absolute left-3 transition-all duration-200"
                            :class="compact ? 'top-2.5' : 'top-3.5 !w-5 !h-5'"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <button type="button" @click="filtersOpen = !filtersOpen"
                        class="border border-stone-300 rounded-lg hover:bg-stone-100 transition flex items-center gap-1.5"
                        :class="[filtersOpen ? 'bg-green-50 border-green-300' : '', compact ? 'px-3 py-1.5' : 'px-4 py-2.5']">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        <span class="hidden sm:inline text-sm">Filters</span>
                    </button>
                    <button type="submit" class="bg-green-700 text-white rounded-lg hover:bg-green-800 transition font-medium"
                        :class="compact ? 'px-4 py-1.5 text-sm' : 'px-6 py-2.5'">
                        Search
                    </button>
                </div>

                <input type="hidden" name="tag" value="{{ $filters['tag'] ?? '' }}">

                {{-- Tag filters --}}
                @if($tags->isNotEmpty())
                    <div class="flex flex-wrap" :class="compact ? 'gap-1.5' : 'gap-2'">
                        @foreach($tags as $tag)
                            <a href="{{ route('crosses.index', array_merge(request()->except('tag', 'page'), ($filters['tag'] ?? '') === $tag->slug ? [] : ['tag' => $tag->slug])) }}"
                                class="inline-flex items-center gap-1 rounded-full font-medium transition border"
                                :class="compact ? 'px-2.5 py-0.5 text-xs' : 'px-3 py-1.5 text-sm gap-1.5'"
                                style="{{ ($filters['tag'] ?? '') === $tag->slug
                                    ? 'background-color: ' . $tag->color . '; color: white; border-color: transparent;'
                                    : 'color: ' . $tag->color . '; border-color: #e7e5e4; background-color: white;' }}">
                                {{ $tag->name }}
                                <span class="text-xs opacity-75">{{ $tag->crosses_count }}</span>
                            </a>
                        @endforeach
                    </div>
                @endif

                {{-- Expandable filters --}}
                <div x-show="filtersOpen" x-transition class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 bg-white p-4 rounded-lg border border-stone-200">
                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1">Species</label>
                        <select name="species" class="w-full rounded-lg border-stone-300 focus:border-green-500 focus:ring-green-500">
                            <option value="">All Species</option>
                            @foreach($speciesList as $species)
                                <option value="{{ $species }}" {{ ($filters['species'] ?? '') === $species ? 'selected' : '' }}>
                                    {{ $species }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1">Status</label>
                        <select name="status" class="w-full rounded-lg border-stone-300 focus:border-green-500 focus:ring-green-500">
                            <option value="">All Statuses</option>
                            <option value="available" {{ ($filters['status'] ?? '') === 'available' ? 'selected' : '' }}>Available</option>
                            <option value="sold_out" {{ ($filters['status'] ?? '') === 'sold_out' ? 'selected' : '' }}>Sold Out</option>
                            <option value="coming_soon" {{ ($filters['status'] ?? '') === 'coming_soon' ? 'selected' : '' }}>Coming Soon</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1">Min Price</label>
                        <input type="number" name="min_price" value="{{ $filters['min_price'] ?? '' }}"
                            step="0.50" min="0" placeholder="$0"
                            class="w-full rounded-lg border-stone-300 focus:border-green-500 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1">Max Price</label>
                        <input type="number" name="max_price" value="{{ $filters['max_price'] ?? '' }}"
                            step="0.50" min="0" placeholder="$50"
                            class="w-full rounded-lg border-stone-300 focus:border-green-500 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1">Sort By</label>
                        <select name="sort" class="w-full rounded-lg border-stone-300 focus:border-green-500 focus:ring-green-500">
                            <option value="code" {{ ($filters['sort'] ?? 'code') === 'code' ? 'selected' : '' }}>Code</option>
                            <option value="price" {{ ($filters['sort'] ?? '') === 'price' ? 'selected' : '' }}>Price</option>
                            <option value="seed_count" {{ ($filters['sort'] ?? '') === 'seed_count' ? 'selected' : '' }}>Seed Count</option>
                            <option value="created_at" {{ ($filters['sort'] ?? '') === 'created_at' ? 'selected' : '' }}>Date Added</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1">Direction</label>
                        <select name="direction" class="w-full rounded-lg border-stone-300 focus:border-green-500 focus:ring-green-500">
                            <option value="asc" {{ ($filters['direction'] ?? 'asc') === 'asc' ? 'selected' : '' }}>Ascending</option>
                            <option value="desc" {{ ($filters['direction'] ?? '') === 'desc' ? 'selected' : '' }}>Descending</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <a href="{{ route('crosses.index') }}" class="w-full text-center px-4 py-2 text-sm text-stone-600 hover:text-stone-900 border border-stone-300 rounded-lg hover:bg-stone-100 transition">
                            Clear Filters
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Results --}}
        @if($crosses->isEmpty())
            <div class="text-center py-16">
                <svg class="w-16 h-16 text-stone-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <h3 class="text-lg font-medium text-stone-600">No crosses found</h3>
                <p class="text-stone-500 mt-1">Try adjusting your search or filters.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($crosses as $cross)
                    <x-cross-card :cross="$cross" />
                @endforeach
            </div>

            <div class="mt-8">
                {{ $crosses->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>

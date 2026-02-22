<x-layouts.app title="Parent Clones">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <h1 class="text-3xl font-bold text-stone-900">Parent Clones</h1>
        <span class="text-stone-500">{{ $clones->total() }} clones</span>
    </div>

    {{-- Filters --}}
    <form action="{{ route('clones.index') }}" method="GET" class="mb-6">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                    placeholder="Search clones..."
                    class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-stone-300 focus:border-green-500 focus:ring-1 focus:ring-green-500 outline-none">
                <svg class="w-5 h-5 text-stone-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <select name="species" class="rounded-lg border-stone-300 focus:border-green-500 focus:ring-green-500">
                <option value="">All Species</option>
                @foreach($speciesList as $species)
                    <option value="{{ $species }}" {{ ($filters['species'] ?? '') === $species ? 'selected' : '' }}>
                        {{ $species }}
                    </option>
                @endforeach
            </select>
            <input type="hidden" name="tag" value="{{ $filters['tag'] ?? '' }}">
            <button type="submit" class="px-6 py-2.5 bg-green-700 text-white rounded-lg hover:bg-green-800 transition font-medium">
                Search
            </button>
            @if(!empty($filters['search']) || !empty($filters['species']) || !empty($filters['tag']))
                <a href="{{ route('clones.index') }}" class="px-4 py-2.5 text-stone-600 border border-stone-300 rounded-lg hover:bg-stone-100 transition text-center">
                    Clear
                </a>
            @endif
        </div>
    </form>

    {{-- Tag filters --}}
    @if($tags->isNotEmpty())
        <div class="flex flex-wrap gap-2 mb-8">
            @foreach($tags as $tag)
                <a href="{{ route('clones.index', array_merge(request()->except('tag', 'page'), ($filters['tag'] ?? '') === $tag->slug ? [] : ['tag' => $tag->slug])) }}"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium transition border
                        {{ ($filters['tag'] ?? '') === $tag->slug
                            ? 'border-transparent text-white shadow-sm'
                            : 'border-stone-200 bg-white hover:shadow-sm' }}"
                    style="{{ ($filters['tag'] ?? '') === $tag->slug
                        ? 'background-color: ' . $tag->color
                        : 'color: ' . $tag->color }}">
                    {{ $tag->name }}
                    <span class="text-xs opacity-75">{{ $tag->clones_count }}</span>
                </a>
            @endforeach
        </div>
    @endif

    {{-- Results --}}
    @if($clones->isEmpty())
        <div class="text-center py-16">
            <h3 class="text-lg font-medium text-stone-600">No clones found</h3>
            <p class="text-stone-500 mt-1">Try adjusting your search or filters.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($clones as $clone)
                <a href="{{ route('clones.show', $clone) }}"
                    class="block bg-white rounded-xl shadow-sm border border-stone-200 hover:shadow-md hover:border-green-300 transition group overflow-hidden">
                    @php
                        $imageUrl = $clone->main_image_url ?? $clone->primaryImage?->image_url;
                    @endphp
                    <div class="aspect-square bg-stone-100 overflow-hidden" x-data="{ failed: false }">
                        @if($imageUrl)
                            <img x-show="!failed" x-on:error="failed = true"
                                src="{{ $imageUrl }}" alt="{{ $clone->name }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                loading="lazy">
                        @endif
                        <div x-show="{{ $imageUrl ? 'failed' : 'true' }}" class="flex items-center justify-center w-full h-full text-stone-300">
                            <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C9.5 2 8 4.5 8 7c0 1.5.5 3 1.5 4.2C8.5 12.5 7 14.5 7 17c0 3 2.5 5 5 5s5-2 5-5c0-2.5-1.5-4.5-2.5-5.8C15.5 10 16 8.5 16 7c0-2.5-1.5-5-4-5z"/></svg>
                        </div>
                    </div>
                    <div class="p-5">
                        <h3 class="font-semibold text-stone-900 group-hover:text-green-700 transition text-lg">
                            {{ $clone->name }}
                        </h3>
                        <p class="text-sm text-stone-500 italic mt-1">{{ $clone->species }}</p>
                        <div class="flex items-center gap-4 mt-3 text-sm text-stone-600">
                            <span>{{ $clone->crosses_as_mother_count + $clone->crosses_as_father_count }} crosses</span>
                            @if($clone->crosses_as_mother_count > 0)
                                <span class="text-stone-400">{{ $clone->crosses_as_mother_count }} as mother</span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $clones->links() }}
        </div>
    @endif
</x-layouts.app>

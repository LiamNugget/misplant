# Phase 2: Core Browse - List, Search, Filter

## Overview
Phase 2 adds the full user-facing browse experience for the Misplant catalog. Users can browse, search, and filter both seed crosses and parent clones, with detail pages showing relationships and scarcity indicators.

## Routes

| Method | URI | Controller | Name |
|--------|-----|------------|------|
| GET | `/` | HomeController | home |
| GET | `/crosses` | CrossController@index | crosses.index |
| GET | `/crosses/{cross:slug}` | CrossController@show | crosses.show |
| GET | `/clones` | CloneController@index | clones.index |
| GET | `/clones/{cactusClone:slug}` | CloneController@show | clones.show |

## Pages

### Homepage (`/`)
- Catalog stats: total crosses, available, clones, species
- "Almost Gone" section: crosses with <= 100 seeds (hot crosses)
- "Recently Added" section: latest available crosses

### Crosses Index (`/crosses`)
Search and filter all 109 seed crosses.

**Search:** By clone name (mother or father) or cross code.

**Filters:**
- Species (dropdown of all Trichocereus species)
- Status (available, sold out, coming soon)
- Min/Max price range
- Sort by: code, price, seed count, date added
- Sort direction: ascending/descending

**Features:**
- Pagination (24 per page, query string preserved)
- Expandable filter panel (Alpine.js toggle)
- "Clear Filters" link to reset
- Empty state for no results

### Cross Detail (`/crosses/{slug}`)
- Cross code and display name (Mother x Father)
- Price, seed count with accuracy level
- Scarcity badge (color-coded)
- Parent clone links with species info
- Interspecies hybrid indicator
- Related crosses section (shares parent clones)

### Clones Index (`/clones`)
Browse all 50 parent clones.

**Search:** By clone name.
**Filter:** By species.
**Features:** Cross count per clone, pagination.

### Clone Detail (`/clones/{slug}`)
- Clone name, species, description
- Photo gallery (horizontal scroll, Alpine.js error handling)
- All crosses involving this clone (as mother or father)

## Components

### `<x-layouts.app>`
Shared layout with:
- Green navigation bar with responsive mobile menu (Alpine.js)
- Active route highlighting
- SEO meta tags (title, description)
- Footer

### `<x-scarcity-badge>`
Color-coded inventory indicator:
```blade
<x-scarcity-badge level="critical" :count="42" />
```
Levels: plenty (green), medium (yellow), low (orange), critical (red + pulse), sold_out (gray), coming_soon (blue).

### `<x-cross-card>`
Reusable cross listing card:
```blade
<x-cross-card :cross="$cross" />
```
Shows: code, display name, scarcity badge, price, accuracy, interspecies indicator.

## Running Locally

```bash
cd /home/liamdev/projects/misplant
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

Visit http://localhost:8000

## Next Phase
Phase 3: Inventory System - Seed count tracking with accuracy levels, visual scarcity indicators, hot crosses section.

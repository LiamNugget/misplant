# Phase 1: Foundation - Database, Models, Relationships

## Overview
Phase 1 establishes the data layer for the Misplant Catalog application. It creates the database schema, Eloquent models with relationships, and seeds the database with 109 real Trichocereus cactus seed crosses across 50 parent clones.

## Setup

```bash
# Install dependencies
composer install
npm install

# Run migrations and seed
php artisan migrate:fresh --seed
```

## Database Schema

### cactus_clones
Parent cactus varieties used in crosses.

| Column | Type | Notes |
|--------|------|-------|
| id | bigint | Primary key |
| name | string | Unique clone name (e.g., "Bridgesii Lee") |
| slug | string | URL-friendly name, auto-generated |
| species | string | Trichocereus species (bridgesii, pachanoi, etc.) |
| description | text | Optional description |
| detail_url | string | Link to original Misplant detail page |
| is_active | boolean | Whether clone is active in catalog |

### crosses
Seed listings - each cross is mother clone x father clone.

| Column | Type | Notes |
|--------|------|-------|
| id | bigint | Primary key |
| code | string | Unique cross code (MIS-001 through MIS-109) |
| slug | string | URL-friendly code, auto-generated |
| mother_clone_id | foreignId | FK to cactus_clones |
| father_clone_id | foreignId | FK to cactus_clones |
| price | decimal(8,2) | Price in USD |
| seed_count | integer | Current seed inventory |
| seed_count_accuracy | enum | estimated, approximate, exact |
| status | enum | available, sold_out, coming_soon |
| description | text | Optional description |

### clone_images
Photo gallery for each clone.

| Column | Type | Notes |
|--------|------|-------|
| id | bigint | Primary key |
| cactus_clone_id | foreignId | FK to cactus_clones |
| image_url | string | Path to image file |
| alt_text | string | Accessibility text |
| is_primary | boolean | Primary display image |
| sort_order | integer | Gallery ordering |

## Models & Relationships

### CactusClone
```php
$clone->images              // HasMany CloneImage (ordered by sort_order)
$clone->primaryImage        // HasOne CloneImage (where is_primary)
$clone->crossesAsMother()   // HasMany Cross (as mother)
$clone->crossesAsFather()   // HasMany Cross (as father)
$clone->allCrosses()        // Builder: all crosses involving this clone

// Scopes
CactusClone::active()
CactusClone::bySpecies('Trichocereus bridgesii')
```

### Cross
```php
$cross->mother              // BelongsTo CactusClone
$cross->father              // BelongsTo CactusClone
$cross->display_name        // "Mother Name x Father Name"
$cross->scarcity_level      // coming_soon|sold_out|critical|low|medium|plenty
$cross->scarcity_color      // blue|gray|red|orange|yellow|green
$cross->relatedCrosses(6)   // Crosses sharing parent clones

// Scopes
Cross::available()
Cross::soldOut()
Cross::hotCrosses(100)      // Available with <= 100 seeds
Cross::byPriceRange(5, 10)

// Note: mother and father are always eager-loaded (protected $with)
```

### CloneImage
```php
$image->cactusClone         // BelongsTo CactusClone
```

## Seeded Data Summary

| Entity | Count |
|--------|-------|
| Clones | 50 |
| Crosses | 109 |
| Clone Images | 20 |
| Species | 14 |
| Available crosses | 99 |
| Sold out | 8 |
| Coming soon | 2 |
| Hot crosses (<=100 seeds) | 18 |

## Scarcity System

Seed counts drive visual indicators:

| Level | Seed Count | Color | CSS Class (future) |
|-------|-----------|-------|---------------------|
| plenty | > 500 | green | `text-green-600` |
| medium | 201-500 | yellow | `text-yellow-500` |
| low | 51-200 | orange | `text-orange-500` |
| critical | 1-50 | red | `text-red-600` |
| sold_out | 0 | gray | `text-gray-400` |
| coming_soon | (any, status-based) | blue | `text-blue-500` |

## Seed Count Accuracy

| Level | Description |
|-------|-------------|
| estimated | Based on weight (~1000 seeds/gram). Used for seed_count > 1000 |
| approximate | Partially counted. Used for seed_count 201-1000 |
| exact | Hand-counted. Used for seed_count <= 200 |

## Next Phase
Phase 2: Core Browse - List pages, search, filter for crosses and clones.

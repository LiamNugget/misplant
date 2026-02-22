# Misplant Catalog

A catalog application for browsing hand-pollinated Trichocereus cactus seed crosses. Built to showcase 50+ parent clones and 100+ seed listings with search, filtering, and inventory tracking.

## Features

- Browse and search seed crosses by parent clone, species, or cross code
- Filter by status, price range, and tags
- Parent clone profiles with photo galleries
- Scarcity indicators based on remaining seed inventory
- Support for open-pollinated (OP) and F2 generation crosses

## Tech Stack

- **Laravel 12** / PHP 8.2+
- **Tailwind CSS 4** / Alpine.js
- **SQLite** (development)
- **Vite** for asset bundling

## Setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan import:misplant
npm run dev
php artisan serve
```

## Routes

| URL | Description |
|-----|-------------|
| `/` | Homepage with stats and featured crosses |
| `/crosses` | Browse all seed crosses with search/filter |
| `/crosses/{slug}` | Cross detail page |
| `/clones` | Browse all parent clones |
| `/clones/{slug}` | Clone detail with gallery and crosses |

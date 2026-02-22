<?php

namespace App\Console\Commands;

use App\Models\CactusClone;
use App\Models\CloneImage;
use App\Models\Cross;
use App\Models\Tag;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportMisplantData extends Command
{
    protected $signature = 'import:misplant {file=misplant-complete-2026-02-09.json}';

    protected $description = 'Import Misplant catalog data from a scraped JSON file';

    private array $cloneIdMap = [];
    private int $imageCount = 0;
    private int $tagCount = 0;
    private int $tagAttachments = 0;

    public function handle(): int
    {
        $filename = $this->argument('file');
        $filepath = storage_path("app/imports/{$filename}");

        if (!file_exists($filepath)) {
            $this->error("File not found: {$filepath}");
            return self::FAILURE;
        }

        $json = file_get_contents($filepath);
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error('Invalid JSON: ' . json_last_error_msg());
            return self::FAILURE;
        }

        if (!isset($data['clones']) || !isset($data['crosses'])) {
            $this->error('JSON must contain "clones" and "crosses" keys.');
            return self::FAILURE;
        }

        $this->info('');
        $this->info('  Misplant Catalog Import');
        $this->info('  =======================');
        $this->info("  File: {$filename}");
        $this->info("  Clones: " . count($data['clones']));
        $this->info("  Crosses: " . count($data['crosses']));
        $this->info('');

        if (!$this->confirm('This will clear existing catalog data and import from JSON. Continue?')) {
            $this->info('Import cancelled.');
            return self::SUCCESS;
        }

        DB::beginTransaction();

        try {
            $this->clearExistingData();
            $this->importClones($data['clones']);
            $this->importCloneImages($data['clones']);
            $this->importCrosses($data['crosses']);
            $this->generateTags($data['clones']);

            DB::commit();

            $this->displayStatistics($data);

            return self::SUCCESS;
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('');
            $this->error("Import failed: {$e->getMessage()}");
            $this->error("File: {$e->getFile()}:{$e->getLine()}");
            $this->error('');
            $this->error('All changes have been rolled back.');

            return self::FAILURE;
        }
    }

    private function clearExistingData(): void
    {
        $this->info('Clearing existing data...');

        // Delete in order to respect foreign key constraints
        DB::table('clone_tag')->truncate();
        Tag::query()->delete();
        Cross::query()->delete();
        CloneImage::query()->delete();
        CactusClone::query()->delete();
    }

    private function importClones(array $clones): void
    {
        $this->info('');
        $bar = $this->output->createProgressBar(count($clones));
        $bar->setFormat("  Importing clones   [%bar%] %current%/%max%");
        $bar->start();

        foreach ($clones as $cloneData) {
            $isMonstrose = $this->detectMonstrose($cloneData['name']);
            $species = $cloneData['species'] ?? $this->extractSpecies($cloneData['name']);

            $clone = CactusClone::create([
                'name' => $cloneData['name'],
                'slug' => $cloneData['slug'] ?? Str::slug($cloneData['name']),
                'species' => $species,
                'description' => $cloneData['description'] ?? null,
                'detail_url' => $cloneData['detailPageUrl'] ?? null,
                'main_image_url' => $cloneData['mainImage'] ?? null,
                'is_monstrose' => $isMonstrose,
                'is_active' => true,
            ]);

            // Map the JSON id to the new database id
            $this->cloneIdMap[$cloneData['id']] = $clone->id;

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }

    private function importCloneImages(array $clones): void
    {
        $this->info('');
        $bar = $this->output->createProgressBar(count($clones));
        $bar->setFormat("  Importing images   [%bar%] %current%/%max% clones");
        $bar->start();

        foreach ($clones as $cloneData) {
            $cloneId = $this->cloneIdMap[$cloneData['id']] ?? null;
            if (!$cloneId) {
                $bar->advance();
                continue;
            }

            $images = $cloneData['images'] ?? [];
            foreach ($images as $index => $imageUrl) {
                $filename = basename(parse_url($imageUrl, PHP_URL_PATH));

                CloneImage::create([
                    'cactus_clone_id' => $cloneId,
                    'image_url' => $imageUrl,
                    'filename' => $filename,
                    'alt_text' => $cloneData['name'],
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);

                $this->imageCount++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }

    private function importCrosses(array $crosses): void
    {
        $this->info('');
        $bar = $this->output->createProgressBar(count($crosses));
        $bar->setFormat("  Importing crosses  [%bar%] %current%/%max%");
        $bar->start();

        foreach ($crosses as $crossData) {
            $motherCloneId = isset($crossData['motherId'])
                ? ($this->cloneIdMap[$crossData['motherId']] ?? null)
                : null;
            $fatherCloneId = isset($crossData['fatherId'])
                ? ($this->cloneIdMap[$crossData['fatherId']] ?? null)
                : null;

            $initialSeedCount = $this->calculateSeedCount($crossData['quantityUnit'] ?? null);

            $crossName = $crossData['cross'] ?? null;

            Cross::create([
                'code' => $crossData['code'],
                'mother_clone_id' => $motherCloneId,
                'father_clone_id' => $fatherCloneId,
                'mother_name_text' => $crossData['motherName'] ?? null,
                'father_name_text' => $crossData['fatherName'] ?? null,
                'cross_name' => $crossName,
                'is_op' => $crossData['isOP'] ?? false,
                'is_f2' => $crossData['isF2'] ?? false,
                'price' => $crossData['pricePerUnit'] ?? 0,
                'seed_count' => $initialSeedCount,
                'seed_count_accuracy' => $initialSeedCount > 1000 ? 'estimated' : ($initialSeedCount > 200 ? 'approximate' : 'exact'),
                'quantity_unit' => $crossData['quantityUnit'] ?? null,
                'has_multiple_pricing' => $crossData['hasMultiplePricing'] ?? false,
                'all_prices_json' => $crossData['allPrices'] ?? null,
                'initial_seed_count' => $initialSeedCount,
                'seeds_sold' => 0,
                'manual_adjustment' => 0,
                'status' => $crossData['status'] ?? 'available',
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }

    private function generateTags(array $clones): void
    {
        $this->info('');
        $this->info('  Generating tags...');

        $tagDefinitions = [
            'TPM' => [
                'patterns' => ['tpm'],
                'color' => '#22c55e',
            ],
            'TPC' => [
                'patterns' => ['tpc'],
                'color' => '#3b82f6',
            ],
            'Variegated' => [
                'patterns' => ['variegated'],
                'color' => '#eab308',
            ],
            'Monstrose' => [
                'patterns' => ['monstrose'],
                'color' => '#a855f7',
            ],
            'Blue' => [
                'patterns' => ['blue'],
                'color' => '#06b6d4',
            ],
            'Zelly' => [
                'patterns' => ['zelly'],
                'color' => '#f97316',
            ],
            'Oscar' => [
                'patterns' => ['oscar'],
                'color' => '#ef4444',
            ],
            'Elite Genetics' => [
                'patterns' => ['sharxx', 'sun goddess', 'althea'],
                'color' => '#d946ef',
            ],
            'Collector Clone' => [
                'patterns' => ['ss02', 'kgc'],
                'color' => '#f59e0b',
            ],
        ];

        foreach ($tagDefinitions as $tagName => $definition) {
            $tag = Tag::create([
                'name' => $tagName,
                'slug' => Str::slug($tagName),
                'color' => $definition['color'],
            ]);
            $this->tagCount++;

            // Find matching clones
            $matchingCloneIds = [];
            foreach ($clones as $cloneData) {
                $nameLower = strtolower($cloneData['name']);
                foreach ($definition['patterns'] as $pattern) {
                    if (str_contains($nameLower, strtolower($pattern))) {
                        $dbId = $this->cloneIdMap[$cloneData['id']] ?? null;
                        if ($dbId) {
                            $matchingCloneIds[] = $dbId;
                        }
                        break; // One match is enough per clone
                    }
                }
            }

            if (!empty($matchingCloneIds)) {
                $tag->clones()->attach($matchingCloneIds);
                $this->tagAttachments += count($matchingCloneIds);
            }

            $matchCount = count($matchingCloneIds);
            $this->line("    {$tagName}: {$matchCount} clones");
        }
    }

    private function calculateSeedCount(?string $quantityUnit): int
    {
        if ($quantityUnit === null) {
            return 0;
        }

        $unit = strtolower(trim($quantityUnit));

        // Check for gram-based quantities: "~1 gram", "1 gram", "3.4g", etc.
        if (preg_match('/([0-9]*\.?[0-9]+)\s*g(?:ram)?/i', $unit, $matches)) {
            return (int) round((float) $matches[1] * 1000);
        }

        // Check for numeric-only quantities: "100", "30", etc.
        if (preg_match('/^~?(\d+)$/', trim($quantityUnit), $matches)) {
            return (int) $matches[1];
        }

        return 0;
    }

    private function detectMonstrose(string $name): bool
    {
        $lower = strtolower($name);
        return str_contains($lower, 'monstrose') || str_contains($lower, 'monster');
    }

    private function extractSpecies(string $name): ?string
    {
        $speciesMap = [
            'bridgesii' => 'bridgesii',
            'pachanoi' => 'pachanoi',
            'peruvianus' => 'peruvianus',
            'scopulicola' => 'scopulicola',
            'cuzcoensis' => 'cuzcoensis',
            'terscheckii' => 'terscheckii',
            'macrogonus' => 'macrogonus',
            'validus' => 'validus',
            'knuthianus' => 'knuthianus',
            'werdermannianus' => 'werdermannianus',
            'huanucoensis' => 'huanucoensis',
            'tulhuayacensis' => 'tulhuayacensis',
            'puquiensis' => 'puquiensis',
            'santaensis' => 'santaensis',
        ];

        $lower = strtolower($name);
        foreach ($speciesMap as $keyword => $species) {
            if (str_contains($lower, $keyword)) {
                return $species;
            }
        }

        return null;
    }

    private function displayStatistics(array $data): void
    {
        $totalClones = CactusClone::count();
        $totalCrosses = Cross::count();
        $totalImages = CloneImage::count();
        $availableCrosses = Cross::where('status', 'available')->count();
        $soldOutCrosses = Cross::where('status', 'sold_out')->count();
        $avgImages = $totalClones > 0 ? round($totalImages / $totalClones, 1) : 0;

        $this->info('');
        $this->info('  ============================');
        $this->info('  Import Complete!');
        $this->info('  ============================');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Clones imported', $totalClones],
                ['Crosses imported', $totalCrosses],
                ['Images imported', $totalImages],
                ['Tags created', $this->tagCount],
                ['Tag assignments', $this->tagAttachments],
                ['Available crosses', $availableCrosses],
                ['Sold out crosses', $soldOutCrosses],
                ['Avg images per clone', $avgImages],
            ]
        );
        $this->info('');
    }
}

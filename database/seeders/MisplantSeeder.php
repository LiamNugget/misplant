<?php

namespace Database\Seeders;

use App\Models\CactusClone;
use App\Models\CloneImage;
use App\Models\Cross;
use Illuminate\Database\Seeder;

class MisplantSeeder extends Seeder
{
    public function run(): void
    {
        $clones = $this->seedClones();
        $this->seedCrosses($clones);
        $this->seedCloneImages($clones);
    }

    private function seedClones(): array
    {
        $cloneData = [
            // Bridgesii varieties
            ['name' => 'Bridgesii Lee', 'species' => 'Trichocereus bridgesii'],
            ['name' => 'Bridgesii Ben', 'species' => 'Trichocereus bridgesii'],
            ['name' => 'Bridgesii Eileen', 'species' => 'Trichocereus bridgesii'],
            ['name' => 'Bridgesii SS02', 'species' => 'Trichocereus bridgesii'],
            ['name' => 'Bridgesii SS01', 'species' => 'Trichocereus bridgesii'],
            ['name' => 'Bridgesii Psycho0', 'species' => 'Trichocereus bridgesii'],
            ['name' => 'Bridgesii Lumberjack', 'species' => 'Trichocereus bridgesii'],
            ['name' => 'Bridgesii Tig', 'species' => 'Trichocereus bridgesii'],
            ['name' => 'Bridgesii Hans', 'species' => 'Trichocereus bridgesii'],
            ['name' => 'Bridgesii Jiimz', 'species' => 'Trichocereus bridgesii'],
            ['name' => 'Bridgesii Short Spine', 'species' => 'Trichocereus bridgesii'],
            ['name' => 'Bridgesii Monstrose', 'species' => 'Trichocereus bridgesii'],

            // Pachanoi varieties
            ['name' => 'TPM', 'species' => 'Trichocereus pachanoi'],
            ['name' => 'Pachanoi Landfill', 'species' => 'Trichocereus pachanoi'],
            ['name' => 'Pachanoi Yowie', 'species' => 'Trichocereus pachanoi'],
            ['name' => 'Pachanoi Fields', 'species' => 'Trichocereus pachanoi'],
            ['name' => 'Pachanoi Matucana', 'species' => 'Trichocereus pachanoi'],
            ['name' => 'Pachanoi PC', 'species' => 'Trichocereus pachanoi'],
            ['name' => 'Pachanoi Malo', 'species' => 'Trichocereus pachanoi'],
            ['name' => 'Pachanoi Juuls Giant', 'species' => 'Trichocereus pachanoi'],
            ['name' => 'Pachanoi Icaro', 'species' => 'Trichocereus pachanoi'],
            ['name' => 'Pachanoi Nitrogen', 'species' => 'Trichocereus pachanoi'],
            ['name' => 'Pachanoi Torres & Torres', 'species' => 'Trichocereus pachanoi'],
            ['name' => 'Pachanoi Ogun', 'species' => 'Trichocereus pachanoi'],

            // Peruvianus varieties
            ['name' => 'Peruvianus Rosei 1', 'species' => 'Trichocereus peruvianus'],
            ['name' => 'Peruvianus Rosei 2', 'species' => 'Trichocereus peruvianus'],
            ['name' => 'Peruvianus Serra Blue', 'species' => 'Trichocereus peruvianus'],
            ['name' => 'Peruvianus Pichu', 'species' => 'Trichocereus peruvianus'],
            ['name' => 'Peruvianus Sharxx Blue', 'species' => 'Trichocereus peruvianus'],
            ['name' => 'Peruvianus Rod', 'species' => 'Trichocereus peruvianus'],
            ['name' => 'Peruvianus Bouncing Bear', 'species' => 'Trichocereus peruvianus'],
            ['name' => 'Peruvianus Len', 'species' => 'Trichocereus peruvianus'],
            ['name' => 'Peruvianus Zed', 'species' => 'Trichocereus peruvianus'],
            ['name' => 'Peruvianus Clyde', 'species' => 'Trichocereus peruvianus'],

            // Scopulicola varieties
            ['name' => 'Scopulicola', 'species' => 'Trichocereus scopulicola'],
            ['name' => 'Scopulicola Harry', 'species' => 'Trichocereus scopulicola'],
            ['name' => 'Scopulicola Hulk', 'species' => 'Trichocereus scopulicola'],

            // Cuzcoensis varieties
            ['name' => 'Cuzcoensis', 'species' => 'Trichocereus cuzcoensis'],
            ['name' => 'Cuzcoensis Super Pedro', 'species' => 'Trichocereus cuzcoensis'],

            // Terscheckii
            ['name' => 'Terscheckii', 'species' => 'Trichocereus terscheckii'],
            ['name' => 'Terscheckii Montana', 'species' => 'Trichocereus terscheckii'],

            // Macrogonus
            ['name' => 'Macrogonus', 'species' => 'Trichocereus macrogonus'],
            ['name' => 'Macrogonus KGC', 'species' => 'Trichocereus macrogonus'],

            // Other notable clones
            ['name' => 'Validus', 'species' => 'Trichocereus validus'],
            ['name' => 'Knuthianus', 'species' => 'Trichocereus knuthianus'],
            ['name' => 'Werdermannianus', 'species' => 'Trichocereus werdermannianus'],
            ['name' => 'Huanucoensis', 'species' => 'Trichocereus huanucoensis'],
            ['name' => 'Tulhuayacensis', 'species' => 'Trichocereus tulhuayacensis'],
            ['name' => 'Puquiensis', 'species' => 'Trichocereus puquiensis'],
            ['name' => 'Santaensis', 'species' => 'Trichocereus santaensis'],
        ];

        $cloneMap = [];
        foreach ($cloneData as $data) {
            $clone = CactusClone::create($data);
            $cloneMap[$data['name']] = $clone;
        }

        return $cloneMap;
    }

    private function seedCrosses(array $clones): void
    {
        $crossData = [
            // Bridgesii crosses
            ['mother' => 'Bridgesii Lee', 'father' => 'Bridgesii Ben', 'code' => 'MIS-001', 'price' => 5.00, 'seed_count' => 3400, 'status' => 'available'],
            ['mother' => 'Bridgesii Lee', 'father' => 'Bridgesii Eileen', 'code' => 'MIS-002', 'price' => 6.00, 'seed_count' => 2800, 'status' => 'available'],
            ['mother' => 'Bridgesii Lee', 'father' => 'TPM', 'code' => 'MIS-003', 'price' => 7.00, 'seed_count' => 1200, 'status' => 'available'],
            ['mother' => 'Bridgesii SS02', 'father' => 'Bridgesii Lee', 'code' => 'MIS-004', 'price' => 8.00, 'seed_count' => 900, 'status' => 'available'],
            ['mother' => 'Bridgesii SS02', 'father' => 'Bridgesii Psycho0', 'code' => 'MIS-005', 'price' => 7.50, 'seed_count' => 340, 'status' => 'available'],
            ['mother' => 'Bridgesii Eileen', 'father' => 'Bridgesii Lumberjack', 'code' => 'MIS-006', 'price' => 6.50, 'seed_count' => 2100, 'status' => 'available'],
            ['mother' => 'Bridgesii Eileen', 'father' => 'Bridgesii Lee', 'code' => 'MIS-007', 'price' => 6.00, 'seed_count' => 1800, 'status' => 'available'],
            ['mother' => 'Bridgesii Psycho0', 'father' => 'Bridgesii Tig', 'code' => 'MIS-008', 'price' => 9.00, 'seed_count' => 75, 'status' => 'available'],
            ['mother' => 'Bridgesii Hans', 'father' => 'Bridgesii SS01', 'code' => 'MIS-009', 'price' => 8.50, 'seed_count' => 450, 'status' => 'available'],
            ['mother' => 'Bridgesii Jiimz', 'father' => 'Bridgesii Lee', 'code' => 'MIS-010', 'price' => 7.00, 'seed_count' => 0, 'status' => 'sold_out'],
            ['mother' => 'Bridgesii Short Spine', 'father' => 'Bridgesii Monstrose', 'code' => 'MIS-011', 'price' => 10.00, 'seed_count' => 180, 'status' => 'available'],
            ['mother' => 'Bridgesii Ben', 'father' => 'Bridgesii Eileen', 'code' => 'MIS-012', 'price' => 5.50, 'seed_count' => 3100, 'status' => 'available'],
            ['mother' => 'Bridgesii Lumberjack', 'father' => 'Bridgesii SS02', 'code' => 'MIS-013', 'price' => 7.50, 'seed_count' => 640, 'status' => 'available'],
            ['mother' => 'Bridgesii Tig', 'father' => 'Bridgesii Psycho0', 'code' => 'MIS-014', 'price' => 9.50, 'seed_count' => 40, 'status' => 'available'],

            // Pachanoi crosses
            ['mother' => 'TPM', 'father' => 'Pachanoi Landfill', 'code' => 'MIS-015', 'price' => 5.00, 'seed_count' => 4200, 'status' => 'available'],
            ['mother' => 'TPM', 'father' => 'Pachanoi Yowie', 'code' => 'MIS-016', 'price' => 6.00, 'seed_count' => 2600, 'status' => 'available'],
            ['mother' => 'TPM', 'father' => 'Bridgesii Lee', 'code' => 'MIS-017', 'price' => 7.00, 'seed_count' => 1500, 'status' => 'available'],
            ['mother' => 'Pachanoi Landfill', 'father' => 'TPM', 'code' => 'MIS-018', 'price' => 5.50, 'seed_count' => 3800, 'status' => 'available'],
            ['mother' => 'Pachanoi Landfill', 'father' => 'Pachanoi Fields', 'code' => 'MIS-019', 'price' => 5.00, 'seed_count' => 2900, 'status' => 'available'],
            ['mother' => 'Pachanoi Yowie', 'father' => 'Pachanoi Matucana', 'code' => 'MIS-020', 'price' => 7.50, 'seed_count' => 550, 'status' => 'available'],
            ['mother' => 'Pachanoi Fields', 'father' => 'Pachanoi PC', 'code' => 'MIS-021', 'price' => 5.00, 'seed_count' => 4500, 'status' => 'available'],
            ['mother' => 'Pachanoi Matucana', 'father' => 'Pachanoi Malo', 'code' => 'MIS-022', 'price' => 8.00, 'seed_count' => 320, 'status' => 'available'],
            ['mother' => 'Pachanoi Juuls Giant', 'father' => 'Pachanoi Icaro', 'code' => 'MIS-023', 'price' => 9.00, 'seed_count' => 150, 'status' => 'available'],
            ['mother' => 'Pachanoi Icaro', 'father' => 'Pachanoi Nitrogen', 'code' => 'MIS-024', 'price' => 8.50, 'seed_count' => 220, 'status' => 'available'],
            ['mother' => 'Pachanoi Ogun', 'father' => 'Pachanoi Torres & Torres', 'code' => 'MIS-025', 'price' => 10.00, 'seed_count' => 85, 'status' => 'available'],
            ['mother' => 'Pachanoi Torres & Torres', 'father' => 'TPM', 'code' => 'MIS-026', 'price' => 7.00, 'seed_count' => 0, 'status' => 'sold_out'],
            ['mother' => 'Pachanoi Malo', 'father' => 'Pachanoi Ogun', 'code' => 'MIS-027', 'price' => 8.00, 'seed_count' => 410, 'status' => 'available'],
            ['mother' => 'Pachanoi PC', 'father' => 'Pachanoi Landfill', 'code' => 'MIS-028', 'price' => 4.50, 'seed_count' => 5200, 'status' => 'available'],
            ['mother' => 'Pachanoi Nitrogen', 'father' => 'Pachanoi Juuls Giant', 'code' => 'MIS-029', 'price' => 9.00, 'seed_count' => 30, 'status' => 'available'],

            // Peruvianus crosses
            ['mother' => 'Peruvianus Rosei 1', 'father' => 'Peruvianus Rosei 2', 'code' => 'MIS-030', 'price' => 6.00, 'seed_count' => 2200, 'status' => 'available'],
            ['mother' => 'Peruvianus Rosei 1', 'father' => 'Peruvianus Serra Blue', 'code' => 'MIS-031', 'price' => 7.00, 'seed_count' => 1100, 'status' => 'available'],
            ['mother' => 'Peruvianus Serra Blue', 'father' => 'Peruvianus Pichu', 'code' => 'MIS-032', 'price' => 8.00, 'seed_count' => 480, 'status' => 'available'],
            ['mother' => 'Peruvianus Sharxx Blue', 'father' => 'Peruvianus Rod', 'code' => 'MIS-033', 'price' => 9.50, 'seed_count' => 95, 'status' => 'available'],
            ['mother' => 'Peruvianus Pichu', 'father' => 'Peruvianus Sharxx Blue', 'code' => 'MIS-034', 'price' => 8.50, 'seed_count' => 260, 'status' => 'available'],
            ['mother' => 'Peruvianus Bouncing Bear', 'father' => 'Peruvianus Len', 'code' => 'MIS-035', 'price' => 7.00, 'seed_count' => 720, 'status' => 'available'],
            ['mother' => 'Peruvianus Rod', 'father' => 'Peruvianus Rosei 1', 'code' => 'MIS-036', 'price' => 7.50, 'seed_count' => 380, 'status' => 'available'],
            ['mother' => 'Peruvianus Len', 'father' => 'Peruvianus Zed', 'code' => 'MIS-037', 'price' => 8.00, 'seed_count' => 190, 'status' => 'available'],
            ['mother' => 'Peruvianus Zed', 'father' => 'Peruvianus Clyde', 'code' => 'MIS-038', 'price' => 9.00, 'seed_count' => 55, 'status' => 'available'],
            ['mother' => 'Peruvianus Clyde', 'father' => 'Peruvianus Bouncing Bear', 'code' => 'MIS-039', 'price' => 7.50, 'seed_count' => 0, 'status' => 'sold_out'],
            ['mother' => 'Peruvianus Rosei 2', 'father' => 'Peruvianus Sharxx Blue', 'code' => 'MIS-040', 'price' => 8.00, 'seed_count' => 310, 'status' => 'available'],

            // Inter-species crosses (Bridgesii x Pachanoi)
            ['mother' => 'Bridgesii Lee', 'father' => 'Pachanoi Landfill', 'code' => 'MIS-041', 'price' => 7.00, 'seed_count' => 1400, 'status' => 'available'],
            ['mother' => 'Bridgesii Lee', 'father' => 'Pachanoi Ogun', 'code' => 'MIS-042', 'price' => 9.00, 'seed_count' => 280, 'status' => 'available'],
            ['mother' => 'Bridgesii Eileen', 'father' => 'TPM', 'code' => 'MIS-043', 'price' => 7.50, 'seed_count' => 950, 'status' => 'available'],
            ['mother' => 'Bridgesii SS02', 'father' => 'Pachanoi Yowie', 'code' => 'MIS-044', 'price' => 8.50, 'seed_count' => 160, 'status' => 'available'],
            ['mother' => 'Bridgesii Psycho0', 'father' => 'Pachanoi Juuls Giant', 'code' => 'MIS-045', 'price' => 11.00, 'seed_count' => 45, 'status' => 'available'],
            ['mother' => 'TPM', 'father' => 'Bridgesii SS02', 'code' => 'MIS-046', 'price' => 8.00, 'seed_count' => 670, 'status' => 'available'],
            ['mother' => 'TPM', 'father' => 'Bridgesii Eileen', 'code' => 'MIS-047', 'price' => 7.00, 'seed_count' => 1100, 'status' => 'available'],
            ['mother' => 'Pachanoi Ogun', 'father' => 'Bridgesii Lee', 'code' => 'MIS-048', 'price' => 9.50, 'seed_count' => 120, 'status' => 'available'],
            ['mother' => 'Pachanoi Icaro', 'father' => 'Bridgesii Psycho0', 'code' => 'MIS-049', 'price' => 10.00, 'seed_count' => 0, 'status' => 'sold_out'],
            ['mother' => 'Pachanoi Juuls Giant', 'father' => 'Bridgesii Hans', 'code' => 'MIS-050', 'price' => 9.00, 'seed_count' => 210, 'status' => 'available'],

            // Inter-species crosses (Bridgesii x Peruvianus)
            ['mother' => 'Bridgesii Lee', 'father' => 'Peruvianus Sharxx Blue', 'code' => 'MIS-051', 'price' => 10.00, 'seed_count' => 130, 'status' => 'available'],
            ['mother' => 'Bridgesii Eileen', 'father' => 'Peruvianus Rosei 1', 'code' => 'MIS-052', 'price' => 8.00, 'seed_count' => 520, 'status' => 'available'],
            ['mother' => 'Bridgesii SS02', 'father' => 'Peruvianus Serra Blue', 'code' => 'MIS-053', 'price' => 9.00, 'seed_count' => 240, 'status' => 'available'],
            ['mother' => 'Peruvianus Rosei 1', 'father' => 'Bridgesii Lee', 'code' => 'MIS-054', 'price' => 8.50, 'seed_count' => 380, 'status' => 'available'],
            ['mother' => 'Peruvianus Sharxx Blue', 'father' => 'Bridgesii Psycho0', 'code' => 'MIS-055', 'price' => 11.50, 'seed_count' => 25, 'status' => 'available'],

            // Inter-species crosses (Pachanoi x Peruvianus)
            ['mother' => 'TPM', 'father' => 'Peruvianus Rosei 1', 'code' => 'MIS-056', 'price' => 7.50, 'seed_count' => 890, 'status' => 'available'],
            ['mother' => 'TPM', 'father' => 'Peruvianus Sharxx Blue', 'code' => 'MIS-057', 'price' => 9.00, 'seed_count' => 350, 'status' => 'available'],
            ['mother' => 'Pachanoi Landfill', 'father' => 'Peruvianus Serra Blue', 'code' => 'MIS-058', 'price' => 7.00, 'seed_count' => 1300, 'status' => 'available'],
            ['mother' => 'Pachanoi Ogun', 'father' => 'Peruvianus Pichu', 'code' => 'MIS-059', 'price' => 10.00, 'seed_count' => 70, 'status' => 'available'],
            ['mother' => 'Peruvianus Pichu', 'father' => 'TPM', 'code' => 'MIS-060', 'price' => 8.00, 'seed_count' => 430, 'status' => 'available'],
            ['mother' => 'Peruvianus Rosei 2', 'father' => 'Pachanoi Matucana', 'code' => 'MIS-061', 'price' => 8.50, 'seed_count' => 290, 'status' => 'available'],

            // Scopulicola crosses
            ['mother' => 'Scopulicola', 'father' => 'TPM', 'code' => 'MIS-062', 'price' => 8.00, 'seed_count' => 580, 'status' => 'available'],
            ['mother' => 'Scopulicola', 'father' => 'Bridgesii Lee', 'code' => 'MIS-063', 'price' => 9.00, 'seed_count' => 310, 'status' => 'available'],
            ['mother' => 'Scopulicola Harry', 'father' => 'Peruvianus Rosei 1', 'code' => 'MIS-064', 'price' => 10.00, 'seed_count' => 140, 'status' => 'available'],
            ['mother' => 'Scopulicola Hulk', 'father' => 'Bridgesii Eileen', 'code' => 'MIS-065', 'price' => 10.50, 'seed_count' => 60, 'status' => 'available'],
            ['mother' => 'Scopulicola Harry', 'father' => 'Scopulicola Hulk', 'code' => 'MIS-066', 'price' => 11.00, 'seed_count' => 35, 'status' => 'available'],
            ['mother' => 'TPM', 'father' => 'Scopulicola', 'code' => 'MIS-067', 'price' => 8.00, 'seed_count' => 510, 'status' => 'available'],
            ['mother' => 'Bridgesii Lee', 'father' => 'Scopulicola Harry', 'code' => 'MIS-068', 'price' => 9.50, 'seed_count' => 0, 'status' => 'sold_out'],

            // Cuzcoensis crosses
            ['mother' => 'Cuzcoensis', 'father' => 'TPM', 'code' => 'MIS-069', 'price' => 8.00, 'seed_count' => 480, 'status' => 'available'],
            ['mother' => 'Cuzcoensis', 'father' => 'Bridgesii Lee', 'code' => 'MIS-070', 'price' => 9.00, 'seed_count' => 250, 'status' => 'available'],
            ['mother' => 'Cuzcoensis Super Pedro', 'father' => 'Pachanoi Ogun', 'code' => 'MIS-071', 'price' => 11.00, 'seed_count' => 90, 'status' => 'available'],
            ['mother' => 'Cuzcoensis Super Pedro', 'father' => 'Peruvianus Sharxx Blue', 'code' => 'MIS-072', 'price' => 12.00, 'seed_count' => 20, 'status' => 'available'],

            // Terscheckii crosses
            ['mother' => 'Terscheckii', 'father' => 'TPM', 'code' => 'MIS-073', 'price' => 7.00, 'seed_count' => 1600, 'status' => 'available'],
            ['mother' => 'Terscheckii', 'father' => 'Bridgesii Lee', 'code' => 'MIS-074', 'price' => 8.00, 'seed_count' => 700, 'status' => 'available'],
            ['mother' => 'Terscheckii Montana', 'father' => 'Peruvianus Rosei 1', 'code' => 'MIS-075', 'price' => 9.00, 'seed_count' => 200, 'status' => 'available'],
            ['mother' => 'Terscheckii Montana', 'father' => 'Scopulicola', 'code' => 'MIS-076', 'price' => 10.00, 'seed_count' => 110, 'status' => 'available'],

            // Macrogonus crosses
            ['mother' => 'Macrogonus', 'father' => 'TPM', 'code' => 'MIS-077', 'price' => 6.50, 'seed_count' => 2100, 'status' => 'available'],
            ['mother' => 'Macrogonus', 'father' => 'Bridgesii Eileen', 'code' => 'MIS-078', 'price' => 7.50, 'seed_count' => 830, 'status' => 'available'],
            ['mother' => 'Macrogonus KGC', 'father' => 'Peruvianus Serra Blue', 'code' => 'MIS-079', 'price' => 9.00, 'seed_count' => 170, 'status' => 'available'],
            ['mother' => 'Macrogonus KGC', 'father' => 'Pachanoi Juuls Giant', 'code' => 'MIS-080', 'price' => 10.00, 'seed_count' => 0, 'status' => 'sold_out'],

            // Rare species crosses
            ['mother' => 'Validus', 'father' => 'TPM', 'code' => 'MIS-081', 'price' => 9.00, 'seed_count' => 300, 'status' => 'available'],
            ['mother' => 'Validus', 'father' => 'Bridgesii Lee', 'code' => 'MIS-082', 'price' => 10.00, 'seed_count' => 140, 'status' => 'available'],
            ['mother' => 'Knuthianus', 'father' => 'Pachanoi Landfill', 'code' => 'MIS-083', 'price' => 8.50, 'seed_count' => 420, 'status' => 'available'],
            ['mother' => 'Knuthianus', 'father' => 'Peruvianus Rosei 1', 'code' => 'MIS-084', 'price' => 9.50, 'seed_count' => 180, 'status' => 'available'],
            ['mother' => 'Werdermannianus', 'father' => 'TPM', 'code' => 'MIS-085', 'price' => 10.00, 'seed_count' => 100, 'status' => 'available'],
            ['mother' => 'Werdermannianus', 'father' => 'Bridgesii SS02', 'code' => 'MIS-086', 'price' => 11.00, 'seed_count' => 50, 'status' => 'available'],
            ['mother' => 'Huanucoensis', 'father' => 'Pachanoi Ogun', 'code' => 'MIS-087', 'price' => 10.50, 'seed_count' => 65, 'status' => 'available'],
            ['mother' => 'Huanucoensis', 'father' => 'Bridgesii Eileen', 'code' => 'MIS-088', 'price' => 9.50, 'seed_count' => 230, 'status' => 'available'],
            ['mother' => 'Tulhuayacensis', 'father' => 'TPM', 'code' => 'MIS-089', 'price' => 9.00, 'seed_count' => 340, 'status' => 'available'],
            ['mother' => 'Tulhuayacensis', 'father' => 'Peruvianus Sharxx Blue', 'code' => 'MIS-090', 'price' => 11.00, 'seed_count' => 0, 'status' => 'sold_out'],
            ['mother' => 'Puquiensis', 'father' => 'Pachanoi Matucana', 'code' => 'MIS-091', 'price' => 10.00, 'seed_count' => 80, 'status' => 'available'],
            ['mother' => 'Puquiensis', 'father' => 'Bridgesii Lee', 'code' => 'MIS-092', 'price' => 10.50, 'seed_count' => 55, 'status' => 'available'],
            ['mother' => 'Santaensis', 'father' => 'TPM', 'code' => 'MIS-093', 'price' => 8.50, 'seed_count' => 460, 'status' => 'available'],
            ['mother' => 'Santaensis', 'father' => 'Peruvianus Pichu', 'code' => 'MIS-094', 'price' => 10.00, 'seed_count' => 120, 'status' => 'available'],

            // Additional popular combinations
            ['mother' => 'Bridgesii Lee', 'father' => 'Pachanoi Juuls Giant', 'code' => 'MIS-095', 'price' => 9.00, 'seed_count' => 200, 'status' => 'available'],
            ['mother' => 'Bridgesii Lee', 'father' => 'Peruvianus Pichu', 'code' => 'MIS-096', 'price' => 8.50, 'seed_count' => 350, 'status' => 'available'],
            ['mother' => 'TPM', 'father' => 'Pachanoi Ogun', 'code' => 'MIS-097', 'price' => 8.00, 'seed_count' => 560, 'status' => 'available'],
            ['mother' => 'TPM', 'father' => 'Cuzcoensis', 'code' => 'MIS-098', 'price' => 8.50, 'seed_count' => 390, 'status' => 'available'],
            ['mother' => 'Pachanoi Yowie', 'father' => 'Bridgesii Lee', 'code' => 'MIS-099', 'price' => 8.00, 'seed_count' => 440, 'status' => 'available'],
            ['mother' => 'Peruvianus Serra Blue', 'father' => 'Bridgesii Eileen', 'code' => 'MIS-100', 'price' => 9.00, 'seed_count' => 160, 'status' => 'available'],
            ['mother' => 'Bridgesii Ben', 'father' => 'TPM', 'code' => 'MIS-101', 'price' => 6.50, 'seed_count' => 1900, 'status' => 'available'],
            ['mother' => 'Pachanoi Fields', 'father' => 'Bridgesii Lee', 'code' => 'MIS-102', 'price' => 7.00, 'seed_count' => 1050, 'status' => 'available'],
            ['mother' => 'Peruvianus Rosei 1', 'father' => 'TPM', 'code' => 'MIS-103', 'price' => 7.50, 'seed_count' => 780, 'status' => 'available'],
            ['mother' => 'Bridgesii Lumberjack', 'father' => 'Pachanoi Icaro', 'code' => 'MIS-104', 'price' => 8.50, 'seed_count' => 270, 'status' => 'available'],
            ['mother' => 'Pachanoi Malo', 'father' => 'Peruvianus Rod', 'code' => 'MIS-105', 'price' => 9.00, 'seed_count' => 0, 'status' => 'sold_out'],
            ['mother' => 'Bridgesii SS01', 'father' => 'Pachanoi Nitrogen', 'code' => 'MIS-106', 'price' => 9.50, 'seed_count' => 110, 'status' => 'available'],
            ['mother' => 'Peruvianus Bouncing Bear', 'father' => 'TPM', 'code' => 'MIS-107', 'price' => 7.50, 'seed_count' => 630, 'status' => 'available'],
            ['mother' => 'Scopulicola', 'father' => 'Peruvianus Sharxx Blue', 'code' => 'MIS-108', 'price' => 11.00, 'seed_count' => 0, 'status' => 'coming_soon'],
            ['mother' => 'Cuzcoensis Super Pedro', 'father' => 'Bridgesii Psycho0', 'code' => 'MIS-109', 'price' => 13.00, 'seed_count' => 0, 'status' => 'coming_soon'],
        ];

        foreach ($crossData as $data) {
            Cross::create([
                'code' => $data['code'],
                'mother_clone_id' => $clones[$data['mother']]->id,
                'father_clone_id' => $clones[$data['father']]->id,
                'price' => $data['price'],
                'seed_count' => $data['seed_count'],
                'seed_count_accuracy' => $data['seed_count'] > 1000 ? 'estimated' : ($data['seed_count'] > 200 ? 'approximate' : 'exact'),
                'status' => $data['status'],
            ]);
        }
    }

    private function seedCloneImages(array $clones): void
    {
        $imageData = [
            'Bridgesii Lee' => [
                'https://misplant.net/photos/Lee.jpg',
            ],
            'Bridgesii Eileen' => [
                'https://misplant.net/photos/eileenMonstrousDSCN3972.jpg',
                'https://misplant.net/photos/eileenBudDSC_0521.jpg',
            ],
            'Bridgesii SS02' => [
                'https://misplant.net/photos/ss02budsDSC_0721.jpg',
                'https://misplant.net/photos/ss02DSC_0092.jpg',
            ],
            'Bridgesii Psycho0' => [
                'https://misplant.net/photos/P0-CuzcoDSC_0190.jpg',
            ],
            'TPM' => [
                'https://misplant.net/photos/tpmBudsDSC_0699.jpg',
                'https://misplant.net/photos/TPM2BudDSC_0630.jpg',
                'https://misplant.net/photos/TPMFLRDSC_0703.jpg',
            ],
            'Pachanoi Landfill' => [
                'https://misplant.net/photos/landfillBudDSCN4573.jpg',
                'https://misplant.net/photos/LandFillBudsDSC_0484.jpg',
            ],
            'Pachanoi Yowie' => [
                'https://misplant.net/photos/Yowie-Buds.jpg',
            ],
            'Pachanoi Ogun' => [
                'https://misplant.net/photos/Ogun.jpg',
            ],
            'Peruvianus Rosei 1' => [
                'https://misplant.net/photos/rosei1DSC0749.jpg',
            ],
            'Peruvianus Sharxx Blue' => [
                'https://misplant.net/photos/sharxxDSCN3196.jpg',
                'https://misplant.net/photos/sharxxBluntBudDSC_0621.jpg',
            ],
            'Peruvianus Serra Blue' => [
                'https://misplant.net/photos/Serra.JPG',
            ],
            'Scopulicola' => [
                'https://misplant.net/photos/Scopulicola4a.jpg',
                'https://misplant.net/photos/scopMPscopDSCN3209.jpg',
                'https://misplant.net/photos/scopFRTSDSC_0832.jpg',
            ],
            'Cuzcoensis Super Pedro' => [
                'https://misplant.net/photos/SuperPedro.jpg',
            ],
            'Validus' => [
                'https://misplant.net/photos/validus2budsDSC_0077.jpg',
                'https://misplant.net/photos/validisDSC_0181.jpg',
                'https://misplant.net/photos/validus1budsDSC_0146.jpg',
            ],
        ];

        foreach ($imageData as $cloneName => $images) {
            if (!isset($clones[$cloneName])) {
                continue;
            }

            foreach ($images as $index => $url) {
                CloneImage::create([
                    'cactus_clone_id' => $clones[$cloneName]->id,
                    'image_url' => $url,
                    'filename' => basename(parse_url($url, PHP_URL_PATH)),
                    'alt_text' => $cloneName,
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
            }
        }
    }
}

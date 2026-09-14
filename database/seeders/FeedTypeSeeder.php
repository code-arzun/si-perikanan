<?php

namespace Database\Seeders;

use App\Models\FeedType;
use Illuminate\Database\Seeder;

class FeedTypeSeeder extends Seeder
{
    public function run(): void
    {
        $feedTypes = [
            [
                'code' => 'PF-800',
                'name' => 'PF 800 (Tebar Bibit)',
                'brand' => 'Matahari Sakti',
                'protein_percentage' => 39.00,
                'pellet_size' => '0.7 - 1.0 mm',
                'description' => 'Pakan awal tebar benih ikan'
            ],
            [
                'code' => 'PF-1000',
                'name' => 'PF 1000',
                'brand' => 'Matahari Sakti',
                'protein_percentage' => 39.00,
                'pellet_size' => '1.3 - 1.7 mm',
                'description' => 'Pakan benih tahap pembesaran awal'
            ],
            [
                'code' => 'LP-1',
                'name' => 'LP 1 (Pelet Pembesaran)',
                'brand' => 'CP Petfood',
                'protein_percentage' => 31.00,
                'pellet_size' => '2.0 mm',
                'description' => 'Pakan apung pembesaran ikan air tawar'
            ],
            [
                'code' => 'LP-2',
                'name' => 'LP 2 (Pelet Pembesaran)',
                'brand' => 'CP Petfood',
                'protein_percentage' => 31.00,
                'pellet_size' => '3.0 mm',
                'description' => 'Pakan apung pembesaran lanjutan'
            ],
        ];

        foreach ($feedTypes as $type) {
            FeedType::firstOrCreate(['code' => $type['code']], $type);
        }
    }
}
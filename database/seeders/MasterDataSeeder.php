<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Master Spesies Ikan
        $species = [
            ['name' => 'Lele Dumbo', 'latin_name' => 'Clarias gariepinus', 'description' => 'Ikan lele konsumsi pertumbuhan cepat'],
            ['name' => 'Nila Merah', 'latin_name' => 'Oreochromis niloticus', 'description' => 'Ikan nila air tawar kualitas unggul'],
            ['name' => 'Gurame', 'latin_name' => 'Osphronemus goramy', 'description' => 'Ikan air tawar ekonomis tinggi'],
            ['name' => 'Patin', 'latin_name' => 'Pangasius sp.', 'description' => 'Ikan patin konsumsi daging putih'],
            ['name' => 'Bawal', 'latin_name' => 'Colossoma macropomum', 'description' => 'Ikan bawal air tawar'],
        ];

        foreach ($species as $item) {
            DB::table('fish_species')->updateOrInsert(
                ['name' => $item['name']],
                array_merge($item, ['is_active' => true, 'created_at' => now(), 'updated_at' => now()])
            );
        }

        // 2. Master Jenis Pakan
        $feedTypes = [
            ['name' => 'Pelet Apung', 'description' => 'Pakan fabrikasi mengapung untuk masa pertumbuhan'],
            ['name' => 'Pelet Tenggelam', 'description' => 'Pakan fabrikasi tenggelam'],
            ['name' => 'Maggot BSF', 'description' => 'Pakan organik tinggi protein dari larva lalat tentara hitam'],
            ['name' => 'Daging / Jeroan Giling', 'description' => 'Pakan alternatif protein tinggi'],
            ['name' => 'Ikan Rucah', 'description' => 'Pakan ikan segar cincang'],
        ];

        foreach ($feedTypes as $item) {
            DB::table('feed_types')->updateOrInsert(
                ['name' => $item['name']],
                array_merge($item, ['is_active' => true, 'created_at' => now(), 'updated_at' => now()])
            );
        }

        // 3. Master Jenis Kolam
        $pondTypes = [
            ['name' => 'Kolam Terpal / Bioflok', 'description' => 'Kolam rangka pipa/besi dengan terpal bulat/persegi'],
            ['name' => 'Kolam Tanah', 'description' => 'Kolam penggalian tanah alami'],
            ['name' => 'Kolam Beton / Semen', 'description' => 'Kolam konstruksi semen permanen'],
            ['name' => 'Keramba Jaring Apung (KJA)', 'description' => 'Keramba terapung di sungai/danau/waduk'],
        ];

        foreach ($pondTypes as $item) {
            DB::table('pond_types')->updateOrInsert(
                ['name' => $item['name']],
                array_merge($item, ['is_active' => true, 'created_at' => now(), 'updated_at' => now()])
            );
        }

        // 4. Master Kategori Arus Kas Default (Global SaaS)
        $cashFlowCategories = [
            // Pemasukan (Income)
            ['name' => 'Penjualan Panen Total', 'type' => 'income', 'is_default' => true],
            ['name' => 'Penjualan Panen Parsial', 'type' => 'income', 'is_default' => true],
            ['name' => 'Penjualan Bibit / Afkir', 'type' => 'income', 'is_default' => true],
            ['name' => 'Modal Awal / Suntikan Dana', 'type' => 'income', 'is_default' => true],
            
            // Pengeluaran (Expense)
            ['name' => 'Pembelian Pakan', 'type' => 'expense', 'is_default' => true],
            ['name' => 'Pembelian Bibit / Benih', 'type' => 'expense', 'is_default' => true],
            ['name' => 'Pembelian Obat / Probiotik / Vitamin', 'type' => 'expense', 'is_default' => true],
            ['name' => 'Listrik & Air', 'type' => 'expense', 'is_default' => true],
            ['name' => 'Gaji Operator / Pegawai', 'type' => 'expense', 'is_default' => true],
            ['name' => 'Maintenance / Perbaikan Kolam', 'type' => 'expense', 'is_default' => true],
        ];

        foreach ($cashFlowCategories as $item) {
            DB::table('cash_flow_categories')->updateOrInsert(
                ['name' => $item['name'], 'tenant_id' => null],
                array_merge($item, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}
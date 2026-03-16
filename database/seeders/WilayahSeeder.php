<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WilayahSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('TRUNCATE TABLE reg_villages, reg_districts, reg_regencies, reg_provinces RESTART IDENTITY CASCADE');

        $path = 'C:\\Users\\User\\OneDrive\\Documents\\SEMESTER 4\\wilayah_indonesia.sql';

        if (!file_exists($path)) {
            $this->command->error('File tidak ditemukan: ' . $path);
            return;
        }

        $this->command->info('Membaca file SQL...');
        $content = file_get_contents($path);

        $tables = [
            'reg_provinces' => ['id', 'name'],
            'reg_regencies' => ['id', 'province_id', 'name'],
            'reg_districts' => ['id', 'regency_id', 'name'],
            'reg_villages'  => ['id', 'district_id', 'name'],
        ];

        foreach ($tables as $tableName => $columns) {
            $start = strpos($content, "INSERT INTO `$tableName`");
            if ($start === false) {
                $this->command->warn("Tabel $tableName tidak ditemukan!");
                continue;
            }
            $end   = strpos($content, ';', $start);
            $chunk = substr($content, $start, $end - $start);

            preg_match_all('/\(\'([^\']+)\',\s*\'([^\']+)\'(?:,\s*\'([^\']+)\')?\)/', $chunk, $rowMatches, PREG_SET_ORDER);

            $rows = [];
            foreach ($rowMatches as $row) {
                if (count($columns) === 2) {
                    $rows[] = [
                        $columns[0] => $row[1],
                        $columns[1] => $row[2],
                    ];
                } else {
                    $rows[] = [
                        $columns[0] => $row[1],
                        $columns[1] => $row[2],
                        $columns[2] => $row[3],
                    ];
                }
            }

            foreach (array_chunk($rows, 500) as $batch) {
                DB::table($tableName)->insert($batch);
            }

            $this->command->info("Tabel $tableName: " . count($rows) . " data diimport!");
        }

        $this->command->info('Selesai!');
    }
}
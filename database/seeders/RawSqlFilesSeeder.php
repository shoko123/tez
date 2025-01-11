<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RawSqlFilesSeeder extends Seeder
{
    public function run(): void
    {
        $files = [
            'global_tags_seeder',
            'areas_seasons_tables_seeder',
            'survey_tables_seeder',
            'locus_tables_seeder',
            'ceramic_tables_seeder',
            'stone_tables_seeder',
            'lithic_tables_seeder',
            'fauna_tables_seeder',
            'metal_tables_seeder',
            'glass_tables_seeder',
            'media_table_seeder',
            'fauna_ro',
        ];

        foreach ($files as $file_name) {
            dump('Running ' . $file_name);
            $path = base_path() . '/database/sql/' . $file_name . '.sql';
            $sql = file_get_contents($path);
            DB::unprepared($sql);
        }
    }
}

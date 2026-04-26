<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QueueSchemasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataPath = __DIR__.'/../../vendor/glacueva/laravel-queue-schema/resources/schemas/data.json';

        // If running in development/testing, also check the package resource path
        if (! file_exists($dataPath)) {
            $dataPath = __DIR__.'/../../resources/schemas/data.json';
        }

        if (! file_exists($dataPath)) {
            $this->command->warn("Data file not found at {$dataPath}");

            return;
        }

        $data = json_decode(file_get_contents($dataPath), true);

        if (! is_array($data)) {
            $this->command->error('Invalid JSON format in data file');

            return;
        }

        DB::table('queue_schemas')->truncate();

        foreach ($data as $schema) {
            DB::table('queue_schemas')->insert(
                [
                    'id' => (string) Str::uuid(),
                    'publisher' => $schema['publisher'],
                    'consumers' => json_encode($schema['consumers'] ?? []),
                    'version' => config('queue-schema.version', '1.0'),
                    'rules' => json_encode($schema['rules'] ?? []),
                ]
            );
        }

        $this->command->info('Queue schemas seeded successfully!');
    }
}

<?php

declare(strict_types=1);

namespace Cortex\Settings\Database\Seeders;

use Illuminate\Database\Seeder;

class CortexSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $abilities = [
            ['name' => 'list', 'title' => 'List settings', 'entity_type' => 'setting'],
            ['name' => 'view', 'title' => 'View settings', 'entity_type' => 'setting'],
            ['name' => 'import', 'title' => 'Import settings', 'entity_type' => 'setting'],
            ['name' => 'export', 'title' => 'Export settings', 'entity_type' => 'setting'],
            ['name' => 'create', 'title' => 'Create settings', 'entity_type' => 'setting'],
            ['name' => 'update', 'title' => 'Update settings', 'entity_type' => 'setting'],
            ['name' => 'delete', 'title' => 'Delete settings', 'entity_type' => 'setting'],
            ['name' => 'audit', 'title' => 'Audit settings', 'entity_type' => 'setting'],
        ];

        collect($abilities)->each(function (array $ability) {
            app('cortex.auth.ability')->firstOrCreate([
                'name' => $ability['name'],
                'entity_type' => $ability['entity_type'],
            ], $ability);
        });
    }
}

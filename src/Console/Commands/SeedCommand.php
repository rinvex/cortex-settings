<?php

declare(strict_types=1);

namespace Cortex\Settings\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Cortex\Settings\Database\Seeders\CortexSettingsSeeder;

#[AsCommand(name: 'cortex:seed:settings')]
class SeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cortex:seed:settings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed Cortex settings Data.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->alert($this->description);

        $this->call('db:seed', ['--class' => CortexSettingsSeeder::class]);

        $this->line('Cortex settings seeders ran successfully');
    }
}

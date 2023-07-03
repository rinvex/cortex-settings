<?php

declare(strict_types=1);

namespace Cortex\Settings\Console\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Rinvex\Settings\Console\Commands\MigrateCommand as BaseMigrateCommand;

#[AsCommand(name: 'cortex:migrate:settings')]
class MigrateCommand extends BaseMigrateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cortex:migrate:settings {--f|force : Force the operation to run when in production.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate Cortex Settings Tables.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        parent::handle();
        $this->alert($this->description);

        $path = config('cortex.settings.autoload_migrations') ?
            realpath(__DIR__.'/../../../database/migrations') :
            $this->laravel->databasePath('migrations/cortex/settings');

        if (file_exists($path)) {
            $this->call('migrate', [
                '--step' => true,
                '--path' => $path,
                '--force' => $this->option('force'),
            ]);
        } else {
            $this->warn('No migrations found! Consider publish them first: <fg=green>php artisan cortex:publish:settings</>');
        }

        $this->line('');
    }
}

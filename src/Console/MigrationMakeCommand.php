<?php

namespace tienhm7\HMVCGenerator\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;

class MigrationMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-migration {name} {--module=} {--table=} {--create=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');

        $moduleName = $this->option('module') ?? $this->ask("Module Name ?");
        $moduleName = ucfirst(Str::camel($moduleName));

        $modulePath = app_path('Modules/' . $moduleName.'/');
        if (!file_exists($modulePath)) {
            $this->error(sprintf('%s module is not exists', $moduleName));
            return;
        }

        $migrationPath = $modulePath . 'Database' . DIRECTORY_SEPARATOR . 'Migrations';
        if (
            !file_exists($migrationPath) &&
            !mkdir($migrationPath, 0777, true) &&
            !is_dir($migrationPath)
        ) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $migrationPath));
        }

        $create = $this->option('create');
        $table = $this->option('table');
        $related_path = 'app/Modules/' . $moduleName . '/Database/Migrations';
        Artisan::call('make:migration '. $name . ' --path='. $related_path . ' --create=' . $create . ' --table=' . $table);
        $this->info('Done !');
    }
}

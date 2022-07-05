<?php

namespace tienhm7\HMVCGenerator\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ControllerMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-controller {name} {--module=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Controllers For Module';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');

        $moduleName = $this->option('module') ?? $this->ask("Module Name ?");
        $moduleName = ucfirst($moduleName);

        $modulePath = app_path('Modules/' . $moduleName.'/');
        // check exist module path
        if (!file_exists($modulePath)) {
            $this->error(sprintf('%s module is not exists', $moduleName));
            return;
        }

        if (
            !file_exists($modulePath . 'Http' . DIRECTORY_SEPARATOR . 'Controllers') &&
            !mkdir($concurrentDirectory = $modulePath . 'Http' . DIRECTORY_SEPARATOR . 'Controllers', 0777, true) &&
            !is_dir($concurrentDirectory)
        ) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }

        $controllerTemplate = str_replace(
            [
                '{{module_name}}',
                '{{module_name_small}}',
                '{{controller_name}}'
            ],
            [
                $moduleName,
                strtolower($moduleName),
                $name
            ],
            get_stub('Controller')
        );

        file_put_contents($modulePath . 'Http' . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . $name . '.php', $controllerTemplate);

        $this->info('Done !');
    }

}

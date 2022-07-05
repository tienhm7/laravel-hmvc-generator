<?php

namespace tienhm7\HMVCGenerator\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ModelMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-model {name} {--module=}';

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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
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

        if (
            !file_exists($modulePath . 'Models') &&
            !mkdir($concurrentDirectory = $modulePath . 'Models', 0777, true) &&
            !is_dir($concurrentDirectory)
        ) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }

        $modelTemplate = str_replace(
            [
                '{{module_name}}',
                '{{module_name_small}}',
                '{{model_name}}'
            ],
            [
                $moduleName,
                strtolower($moduleName),
                $name
            ],
            get_stub('Model')
        );

        file_put_contents($modulePath . 'Models' . DIRECTORY_SEPARATOR . $name.'.php', $modelTemplate);

        $this->info('Done !');
    }
}

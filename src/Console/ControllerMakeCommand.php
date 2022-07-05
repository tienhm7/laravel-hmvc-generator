<?php

namespace tienhm7\HMVCGenerator\Console;

use Illuminate\Console\Command;

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
        $name = $this->argument('module');
        $moduleName = $this->option('module_name') ?? $this->ask("Module Name ?");
        $modulePath = app_path(DIRECTORY_SEPARATOR . 'Modules' . DIRECTORY_SEPARATOR . $moduleName) . DIRECTORY_SEPARATOR;
        if (!file_exists($modulePath)) {
            $this->error(sprintf('%s module is not exists', $moduleName));
            return;
        }
        if (!file_exists($modulePath . 'Http' . DIRECTORY_SEPARATOR . 'Controllers')) {
            mkdir($modulePath . 'Http' . DIRECTORY_SEPARATOR . 'Controllers', 0777, true);
        }
        $controller_template = str_replace(['{{module_name}}', '{{module_name_small}}', '{{controller_name}}'], [$moduleName, strtolower($moduleName), $name], get_stub('Controller'));
        file_put_contents($modulePath . 'Http' . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . $name . '.php', $controller_template);
        $this->info('Done !');
    }

}

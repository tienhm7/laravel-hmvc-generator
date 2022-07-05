<?php

namespace tienhm7\HMVCGenerator\Console;

use Illuminate\Console\Command;

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
        $modulePath = app_path(DIRECTORY_SEPARATOR . 'Modules' . DIRECTORY_SEPARATOR . $moduleName) . DIRECTORY_SEPARATOR;
        if (!file_exists($modulePath)) {
            $this->error(sprintf('%s module is not exists', $moduleName));
            return;
        }
        if (!file_exists($modulePath . 'Models')) {
            mkdir($modulePath . 'Models', 0777, true);
        }
        $model_template = str_replace(['{{module_name}}', '{{module_name_small}}', '{{model_name}}'], [$moduleName, strtolower($moduleName), $name], get_stub('Model'));
        file_put_contents($modulePath . 'Models' . DIRECTORY_SEPARATOR . $name.'.php', $model_template);
        $this->info('Done !');
    }
}

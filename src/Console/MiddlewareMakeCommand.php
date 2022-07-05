<?php

namespace tienhm7\HMVCGenerator\Console;

use Illuminate\Console\Command;

class MiddlewareMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-middleware {name} {--module}';

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
        $modulePath =  app_path(DIRECTORY_SEPARATOR . 'Modules' . DIRECTORY_SEPARATOR . $moduleName) . DIRECTORY_SEPARATOR;
        if (!file_exists($modulePath)) {
            $this->error(sprintf('%s module is not exists', $moduleName));
            return;
        }
        $middleware_path = $modulePath . 'Http' . DIRECTORY_SEPARATOR . 'Middleware';
        if (!file_exists($middleware_path)) {
            mkdir($middleware_path, 0777, true);
        }
        $middleware_template = str_replace(['{{module_name}}', '{{module_name_small}}', '{{middleware_name}}'], [$moduleName, strtolower($moduleName), $name], get_stub('Middleware'));
        file_put_contents($middleware_path . DIRECTORY_SEPARATOR . $name.'.php', $middleware_template);
        $this->info('Done !');
    }
}

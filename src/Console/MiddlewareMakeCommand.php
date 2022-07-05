<?php

namespace tienhm7\HMVCGenerator\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

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
        $moduleName = ucfirst(Str::camel($moduleName));

        $modulePath = app_path('Modules/' . $moduleName.'/');
        if (!file_exists($modulePath)) {
            $this->error(sprintf('%s module is not exists', $moduleName));
            return;
        }
        
        $middlewarePath = $modulePath . 'Http' . DIRECTORY_SEPARATOR . 'Middleware';
        if (
            !file_exists($middlewarePath) &&
            !mkdir($middlewarePath, 0777, true) &&
            !is_dir($middlewarePath)
        ) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $middlewarePath));
        }

        $middlewareTemplate = str_replace(
            [
                '{{module_name}}',
                '{{module_name_small}}',
                '{{middleware_name}}'
            ],
            [
                $moduleName,
                strtolower($moduleName),
                $name
            ],
            get_stub('Middleware')
        );

        file_put_contents($middlewarePath . DIRECTORY_SEPARATOR . $name.'.php', $middlewareTemplate);

        $this->info('Done !');
    }
}

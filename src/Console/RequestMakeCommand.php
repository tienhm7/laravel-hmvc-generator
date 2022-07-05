<?php

namespace tienhm7\HMVCGenerator\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class RequestMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-request {name} {--module}';

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
        
        $requestsPath = $modulePath . 'Http' . DIRECTORY_SEPARATOR . 'Requests';
        if (
            !file_exists($requestsPath) &&
            !mkdir($requestsPath, 0777, true) &&
            !is_dir($requestsPath)
        ) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $requestsPath));
        }
        
        $requestTemplate = str_replace(
            [
                '{{module_name}}',
                '{{module_name_small}}',
                '{{request_name}}'
            ],
            [
                $moduleName,
                strtolower($moduleName),
                $name
            ],
            get_stub('Request')
        );

        file_put_contents($requestsPath . DIRECTORY_SEPARATOR . $name.'.php', $requestTemplate);

        $this->info('Done !');
    }
}

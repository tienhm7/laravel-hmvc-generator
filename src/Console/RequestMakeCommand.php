<?php

namespace tienhm7\HMVCGenerator\Console;

use Illuminate\Console\Command;

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
        $modulePath =  app_path(DIRECTORY_SEPARATOR . 'Modules' . DIRECTORY_SEPARATOR . $moduleName) . DIRECTORY_SEPARATOR;
        if (!file_exists($modulePath)) {
            $this->error(sprintf('%s module is not exists', $moduleName));
            return;
        }
        $requests_path = $modulePath . 'Http' . DIRECTORY_SEPARATOR . 'Requests';
        if (!file_exists($requests_path)) {
            mkdir($requests_path, 0777, true);
        }
        $middleware_template = str_replace(['{{module_name}}', '{{module_name_small}}', '{{request_name}}'], [$moduleName, strtolower($moduleName), $name], get_stub('Request'));
        file_put_contents($requests_path . DIRECTORY_SEPARATOR . $name.'.php', $middleware_template);
        $this->info('Done !');
    }
}

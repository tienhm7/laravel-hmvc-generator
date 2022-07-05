<?php

namespace tienhm7\HMVCGenerator\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Exception\InvalidArgumentException;

class ModuleMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make {module_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create New Module For HMVC Pattern';

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
        $moduleName = $this->argument('module_name');
        $moduleName = ucfirst($moduleName);

        $modulePath = app_path('Modules/' . $moduleName.'/');
        // check exist module path
        if (is_dir($modulePath)) {
            $this->error(sprintf('%s module is not exists', $moduleName));
            return;
        }

        $this->createFolders($moduleName);
        $this->stubFiles($modulePath, $moduleName);
        $this->info('Module created successfully.');
    }

    /**
     * create folder for module
     * @param $moduleName
     * @return void
     */
    private function createFolders($moduleName)
    {
        $neededFolders = array(
            'Config',
            'Database/Migrations',
            'Providers',
            'Resources/Lang/en',
            'Resources/Lang/vi',
            'Resources/Views/' . strtolower($moduleName),
            'Routes',
        );

        // create needed folders
        foreach ($neededFolders as $neededFolder) {
            $path = app_path('Modules/' . $moduleName . "/" . $neededFolder);

            if (!mkdir($path, 0777, true) && !is_dir($path)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $path));
            }

            $this->info("Created $neededFolder in Module $moduleName");
        }

        Artisan::call('module:make-request', ['name' => 'TestRequest', '--module_name' => ucfirst($moduleName)]);
        Artisan::call('module:make-middleware', ['name' => 'TestMiddleware', '--module_name' => ucfirst($moduleName)]);
        Artisan::call('module:make-controller', ['name' => 'TestController', '--module_name' => ucfirst($moduleName)]);
        Artisan::call('module:make-model', ['name' => 'Test', '--module_name' => ucfirst($moduleName)]);
    }

    private function stubFiles($modulePath, $moduleName)
    {
        $service_provider_template = str_replace(['{{module_name}}', '{{module_name_small}}'], [$moduleName, strtolower($moduleName)], get_stub('ServiceProvider'));
        $web_template = str_replace(['{{module_name}}', '{{module_name_small}}'], [$moduleName, strtolower($moduleName)], get_stub('Web'));
        $api_template = str_replace(['{{module_name}}', '{{module_name_small}}'], [$moduleName, strtolower($moduleName)], get_stub('Api'));
        file_put_contents($modulePath . 'Providers/' . $moduleName . 'ServiceProvider.php', $service_provider_template);
        file_put_contents($modulePath . 'Routes/' . 'web.php', $web_template);
        file_put_contents($modulePath . 'Routes/' . 'api.php', $api_template);
        file_put_contents($modulePath . 'Resources/' . 'Views' . DIRECTORY_SEPARATOR . 'test.blade.php', '<h1>' . $moduleName . '</h1>');
    }

}

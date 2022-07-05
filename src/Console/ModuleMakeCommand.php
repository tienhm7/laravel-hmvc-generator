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
        $moduleName = ucfirst(Str::camel($moduleName));

        $modulePath = app_path('Modules/' . $moduleName.'/');
        // check exist module path
        if (is_dir($modulePath)) {
            $this->error(sprintf('%s module is not exists', $moduleName));
            return;
        }

        $this->createFolders($modulePath, $moduleName);
        $this->stubFiles($modulePath, $moduleName);
        $this->info('Module created successfully.');
    }

    /**
     * create folder for module
     * @param $modulePath
     * @param $moduleName
     * @return void
     */
    private function createFolders($modulePath, $moduleName)
    {
        $neededFolders = array(
            'Config',
            'Database/Migrations',
            'Providers',
            'Lang/en',
            'Lang/vi',
            'Views',
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

        Artisan::call('module:make-request', ['name' => $moduleName.'Request', '--module' => ucfirst($moduleName)]);
        Artisan::call('module:make-middleware', ['name' => $moduleName.'Middleware', '--module' => ucfirst($moduleName)]);
        Artisan::call('module:make-controller', ['name' => $moduleName.'Controller', '--module' => ucfirst($moduleName)]);
        Artisan::call('module:make-model', ['name' => $moduleName, '--module' => ucfirst($moduleName)]);
    }

    private function stubFiles($modulePath, $moduleName)
    {
        $serviceProviderTemplate = str_replace(
            [
                '{{module_name}}',
                '{{module_name_small}}'
            ],
            [
                $moduleName,
                strtolower($moduleName)
            ],
            get_stub('ServiceProvider')
        );

        $webTemplate = str_replace(
            [
                '{{module_name}}',
                '{{module_name_small}}'
            ],
            [
                $moduleName,
                strtolower($moduleName)
            ],
            get_stub('Web')
        );

        $apiTemplate = str_replace(
            [
                '{{module_name}}',
                '{{module_name_small}}'
            ],
            [
                $moduleName,
                strtolower($moduleName)
            ],
            get_stub('Api')
        );

        file_put_contents(
            $modulePath . 'Providers/' . $moduleName . 'ServiceProvider.php',
            $serviceProviderTemplate
        );
        file_put_contents($modulePath . 'Routes/' . 'web.php', $webTemplate);
        file_put_contents($modulePath . 'Routes/' . 'api.php', $apiTemplate);
        file_put_contents(
            $modulePath . 'Views/' . 'index.blade.php',
            '<h1>' . $moduleName . '</h1>'
        );
    }

}

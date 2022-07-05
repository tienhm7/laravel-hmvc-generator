<?php

namespace tienhm7\HMVCGenerator;

use Illuminate\Support\ServiceProvider;
use tienhm7\HMVCGenerator\Console\ControllerMakeCommand;
use tienhm7\HMVCGenerator\Console\MiddlewareMakeCommand;
use tienhm7\HMVCGenerator\Console\MigrationMakeCommand;
use tienhm7\HMVCGenerator\Console\ModelMakeCommand;
use tienhm7\HMVCGenerator\Console\ModuleMakeCommand;
use tienhm7\HMVCGenerator\Console\RequestMakeCommand;

class HMVCGeneratorServiceProvider extends ServiceProvider
{

    protected $commands = [
        ModuleMakeCommand::class,
        ControllerMakeCommand::class,
        ModelMakeCommand::class,
        MiddlewareMakeCommand::class,
        RequestMakeCommand::class,
        MigrationMakeCommand::class
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->commands($this->commands);
    }
}

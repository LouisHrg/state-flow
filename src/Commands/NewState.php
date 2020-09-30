<?php

namespace Louishrg\StateFlow\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Console\Command;

class NewState extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'states:new {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add new state namespace to your model';

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
     * @return int
     */
    public function handle()
    {
        $model = $this->argument('model');

        $namespace = $this->ask('What is the your state ?');
        $this->info($namespace);

        return 0;
    }

    protected function buildClass($name)
    {
        $model = $this->option('model');

        if (is_null($model)) {
            $model = $this->laravel->getNamespace().str_replace('/', '\\', $this->argument('name'));
        } elseif (! Str::startsWith($model, [
            $this->laravel->getNamespace(), '\\',
        ])) {
            $model = $this->laravel->getNamespace().$model;
        }

        $resourceName = $this->argument('name');

        if (in_array(strtolower($resourceName), $this->protectedNames)) {
            $this->warn("You *must* override the uriKey method for your {$resourceName} resource.");
        }

        $replace = [
            'DummyFullModel' => $model,
            '{{ namespacedModel }}' => $model,
            '{{namespacedModel}}' => $model,
        ];

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/state.stub');
    }
}

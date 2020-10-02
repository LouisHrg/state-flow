<?php

namespace Louishrg\StateFlow\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class AddState extends Command
{
    protected $signature = 'states:new {model} {target?}';

    protected $description = 'Add new state namespace to your model';

    public function handle()
    {
        $model = $this->argument("model");
        $target = $this->argument("target");

        if (! $target) {
            $modelParts = explode('/', $model);
            end($modelParts);
            $key = key($modelParts);
            $target = $modelParts[$key];
        }

        $this->info("Creating a state for ".$model);
        $attributes = $this->ask('Type for all the properties of your state separated by spaces', null);

        $classNames = $this->ask('List all your available state class name (separated by spaces), e.g "Pending Refused Canceled"');

        $attributesArray = explode(' ', $attributes);
        $classArray = explode(' ', $classNames);

        $guessedPath = base_path('app/Models/States/'.$target);

        if (! is_dir($guessedPath)) {
            (new Filesystem)
            ->makeDirectory(base_path('app/Models/States/'.$target), 0755, true);
        } else {
            $this->error('Directory already exists !');
        }


        foreach ($classArray as $class) {
            $this->buildClass($class, $attributesArray, $guessedPath, $target);
        }

        $this->info('States created in App\Models\States\\'.str_replace('/', '\\', $target));

        return 0;
    }

    public function buildClass($class, $attributes, $guessedPath, $target)
    {
        $stub = file_get_contents(__DIR__.'/stubs/state.stub');

        $targetFile = $guessedPath.'/'.$class.'.php';

        $replace = [
            '{{ properties }}' => $this->getProperties($attributes),
            '{{ namespace }}' => str_replace('/', '\\', $target),
            '{{ class }}' => $class,
        ];

        $newValue = str_replace(
            array_keys($replace),
            array_values($replace),
            $stub
        );

        file_put_contents($targetFile, $newValue);
    }

    public function getProperties($attributes)
    {
        $parsed = '';
        foreach ($attributes as $attribute) {
            $parsed .= '  public $'.$attribute.';'.PHP_EOL;
        }

        return $parsed;
    }
}

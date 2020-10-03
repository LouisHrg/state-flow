<?php

namespace Louishrg\StateFlow\Tests;

use Illuminate\Database\Schema\Blueprint;
use Louishrg\StateFlow\StateFlowServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
             'driver' => 'sqlite',
             'database' => ':memory:',
             'prefix' => '',
         ]);
    }

    protected function setUpDatabase()
    {
        $this->app['db']
        ->connection()
        ->getSchemaBuilder()
        ->create('dummies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('dummy');
            $table->string('flow');
        });

        collect(range(1, 20))->each(function (int $i) {
            Dummy::create([
                 'dummy' => DummyState::class,
             ]);
        });
    }

    protected function getPackageProviders($app)
    {
        return [
          StateFlowServiceProvider::class,
        ];
    }
}

<?php

namespace Louishrg\StateFlow\Tests;

class NewStateCommandTest extends TestCase
{
    /** @test */
    public function testNewStateCommand()
    {
        $this->artisan('states:new App/Models/User')
        ->expectsOutput("Creating a state for App/Models/User")
        ->expectsQuestion(
            "Type for all the properties of your state separated by spaces",
            "key label color"
        )
        ->expectsQuestion(
            'List all your available state class name (separated by spaces), e.g "Pending Refused Canceled"',
            'Pending Refused Canceled'
        )
        ->expectsOutput('States created in App\Models\States\User')
        ->assertExitCode(0);

        $files = [
            'Pending.php',
            'Refused.php',
            'Canceled.php',
        ];

         $basePath = base_path('app/Models/States/User/');

         foreach ($files as $file) {

            $this->assertFileExists($basePath.$file);
            $data = file_get_contents($basePath.$file);

            $this->assertNotFalse(strpos($data, '$key'));
            $this->assertNotFalse(strpos($data, '$label'));
            $this->assertNotFalse(strpos($data, '$color'));
         }


    }
}

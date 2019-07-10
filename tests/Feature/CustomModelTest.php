<?php

namespace Clarkeash\Doorman\Test\Feature;

use Doorman;
use Clarkeash\Doorman\Test\TestCase;
use Clarkeash\Doorman\Test\TestCustomModel;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CustomModelTest extends TestCase
{
    use DatabaseMigrations;

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        $app['config']->set('doorman.invite_model', TestCustomModel::class);
    }

    /**
     * @test
     */
    public function it_can_use_a_custom_model()
    {
        $invites = Doorman::generate()->make();

        $this->assertEquals(TestCustomModel::class, get_class($invites->first()));
    }
}

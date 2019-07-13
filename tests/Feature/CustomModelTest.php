<?php

namespace Clarkeash\Doorman\Test\Feature;

use Clarkeash\Doorman\Models\Invite;
use Doorman;
use Clarkeash\Doorman\Test\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CustomModelTest extends TestCase
{
    use DatabaseMigrations;

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        $app['config']->set('doorman.invite_model', MyCustomModel::class);
    }

    /**
     * @test
     */
    public function it_can_use_a_custom_model()
    {
        $invites = Doorman::generate()->make();

        $this->assertEquals(MyCustomModel::class, get_class($invites->first()));
    }
}

class MyCustomModel extends Invite {

}
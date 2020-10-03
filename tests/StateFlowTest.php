<?php

namespace Louishrg\StateFlow\Tests;

use Exception;
use Louishrg\StateFlow\Stack;
use Louishrg\StateFlow\State;

class StateFlowTest extends TestCase
{
    /** @test */
    public function state_returns_an_object()
    {
        $this->assertEquals(get_class(Dummy::first()->dummy), State::class);
        $this->assertEquals(Dummy::first()->dummy->key, 'dummy');
        $this->assertEquals(Dummy::first()->dummy->label, 'Dummy');
    }

    /** @test */
    public function state_transform_class()
    {
        $dummy = Dummy::first();
        $dummy->dummy = OkDummyState::class;

        $this->assertEquals($dummy->dummy->key, 'okDummy');
        $this->assertEquals($dummy->dummy->label, 'OkDummy');
    }

    /** @test */
    public function model_has_custom_getter()
    {
        $dummy = Dummy::first();
        $this->assertEquals($dummy->_dummy, "dummy");
    }

    /** @test */
    public function model_has_custom_setter()
    {
        $dummy = Dummy::first();
        $dummy->_dummy = 'notDummy';
        $this->assertEquals($dummy->_dummy, "notDummy");
    }

    /** @test */
    public function model_cannot_set_unknown_state_with_setter()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Provided State doesn't exist.");

        $dummy = Dummy::first();
        $dummy->_dummy = 'unknown';
        $dummy->save();
    }

    /** @test */
    public function model_has_states_property()
    {
        $dummy = Dummy::first();
        $this->assertEquals(get_class($dummy::$states['dummy']), Stack::class);
    }

    /** @test */
    public function model_can_find_state()
    {
        $dummy = Dummy::first();
        $foundState = Dummy::findState('dummy', 'dummy');
        $this->assertEquals($foundState, DummyState::class);
    }

    /** @test */
    public function flow_has_default_value()
    {
        $dummy = new Dummy;
        $this->assertEquals(get_class($dummy->flow), State::class);
        $this->assertEquals($dummy->flow->key, 'dummy');
        $this->assertEquals($dummy->flow->label, 'Dummy');
    }

    /** @test */
    public function flow_canBe_method()
    {
        $dummy = new Dummy;
        $this->assertTrue($dummy->flow->canBe(OkDummyState::class));
        $this->assertTrue($dummy->flow->canBe(NotDummyState::class));
        $this->assertFalse($dummy->flow->canBe(UnknownState::class));
    }

    /** @test */
    public function flow_allowedTo_method()
    {
        $dummy = new Dummy;
        $array = [ OkDummyState::class, NotDummyState::class ];

        $this->assertIsArray($dummy->flow->allowedTo());
        $this->assertEquals($dummy->flow->allowedTo(), $array);

        $dummy->_flow = 'okDummy';

        $this->assertIsArray($dummy->flow->allowedTo());
        $this->assertEquals($dummy->flow->allowedTo(), []);
    }

    /** @test */
    public function state_is_method()
    {
        $dummy = Dummy::first();
        $this->assertEquals($dummy->dummy->is(), DummyState::class);
    }

    /** @test */
    public function state_equal_method()
    {
        $dummy = Dummy::first();
        $this->assertTrue($dummy->dummy->equal(DummyState::class));
        $this->assertFalse($dummy->dummy->equal(OkDummyState::class));
    }
}

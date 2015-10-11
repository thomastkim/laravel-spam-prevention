<?php

use Kim\Defender\Laravel\DefenderSession;
use Mockery as m;

class DefenderSessionTest extends PHPUnit_Framework_TestCase {

    protected $session;
    protected $defenderSession;

	public function setUp()
	{
        $this->session = m::mock('Illuminate\Session\Store');
        $this->session->shouldReceive('get')->once();

        $this->defenderSession = new DefenderSession($this->session);
	}

    public function tearDown()
    {
        m::close();
    }

    /** @test */
    public function it_gets_the_value_of_the_key_from_the_session()
    {
        $this->session->shouldReceive('get')->with('email', [])->once();

        $this->defenderSession->get('email');
    }

    /** @test */
    public function it_stores_a_randomized_token_for_a_key_in_the_session()
    {
        $this->session->shouldReceive('put')->once();

        $this->defenderSession->put('email');
    }

    /** @test */
    public function it_retrieves_the_baits_and_flashes_the_new_bait()
    {
        $this->session->shouldReceive('has')->once();
        $this->session->shouldReceive('get')->once();
        $this->session->shouldReceive('flash')->once();

        $this->defenderSession->putBait();
    }
}

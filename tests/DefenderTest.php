<?php

use Kim\Defender\Defender;
use Mockery as m;

class DefenderTest extends PHPUnit_Framework_TestCase {

    protected $session;
    protected $html;

    protected $defender;

	public function setUp()
	{
        $this->session = m::mock('Kim\Defender\Contracts\DefenderSession');
        $this->html = m::mock('Kim\Defender\Contracts\DefenderHtmlGenerator');

        $this->defender = new Defender($this->session, $this->html);
	}

    public function tearDown()
    {
        m::close();
    }

    /** @test */
    public function it_gets_a_randomized_token_for_an_email_input()
    {
        $this->session->shouldReceive('put')->with('email')->once();

        $this->defender->email();
    }

    /** @test */
    public function it_gets_a_randomized_token_for_an_username_input()
    {
        $this->session->shouldReceive('put')->with('username')->once();

        $this->defender->username();
    }

    /** @test */
    public function it_gets_a_randomized_token_for_a_password_input()
    {
        $this->session->shouldReceive('put')->with('password')->once();

        $this->defender->password();
    }

    /** @test */
    public function it_gets_a_randomized_token_for_a_password_confirmation_input()
    {
        $this->session->shouldReceive('put')->with('password_confirmation')->once();

        $this->defender->passwordConfirmation();
    }

    /** @test */
    public function it_gets_a_randomized_token_for_a_given_input()
    {
        $this->session->shouldReceive('put')->with('randomString')->once();

        $this->defender->get('randomString');
    }

    /** @test */
    public function it_gets_all_randomized_tokens()
    {
        $this->session->shouldReceive('all')->once();

        $this->defender->all();
    }

    /** @test */
    public function it_gets_randomized_token_as_bait()
    {
        $this->session->shouldReceive('putBait')->once();

        $this->defender->get('bait');
    }

    /** @test */
    public function it_gets_randomized_token_as_bait_as_well()
    {
        $this->session->shouldReceive('putBait')->once();

        $this->defender->baitToken();
    }

    /** @test */
    public function it_gets_an_input_field_with_a_randomized_token_for_bait()
    {
        $this->session->shouldReceive('putBait')->once();
        $this->html->shouldReceive('generate')->once();

        $this->defender->baitField();
    }

    /** @test */
    public function it_gets_a_set_number_of_input_fields_with_randomized_tokens_for_bait()
    {
        $this->session->shouldReceive('putBait')->times(3);
        $this->html->shouldReceive('generate')->once();

        $this->defender->baitField(3);
    }

    /** @test */
    public function it_gets_a_randomized_number_of_input_fields_with_randomized_tokens_for_bait()
    {
        $this->session->shouldReceive('putBait')->between(1, 5);
        $this->html->shouldReceive('generate')->once();

        $this->defender->baitFields(5);
    }

    /** @test */
    public function it_gets_all_randomized_tokens_that_are_used_for_bait()
    {
        $this->session->shouldReceive('getBait')->once();

        $this->defender->bait();
    }

}

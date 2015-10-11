<?php

use Mockery as m;
use Symfony\Component\HttpFoundation\File\File;

class DefenderValidatorTest extends PHPUnit_Framework_TestCase {

    protected $validator;

	public function setUp()
	{
        $this->validator = m::mock('Kim\Defender\Validation\DefenderValidator')->makePartial();
	}

    public function tearDown()
    {
        m::close();
    }

    /** @test */
    public function it_should_return_true_if_the_bait_input_is_not_populated()
    {
        $this->assertTrue($this->validator->validateReject([], NULL));

        $this->assertTrue($this->validator->validateReject([], ''));

        $this->assertTrue($this->validator->validateReject([], []));

        $file = m::mock('\Symfony\Component\HttpFoundation\File\File', ['getPath' => '']);
        $this->assertTrue($this->validator->validateReject([], $file));
    }

    /** @test */
    public function it_should_return_false_if_the_bait_input_is_populated()
    {
        $this->assertFalse($this->validator->validateReject([], 'randomString'));

        $this->assertFalse($this->validator->validateReject([], [1]));

        $file = m::mock('\Symfony\Component\HttpFoundation\File\File', ['getPath' => 'some/path/test.jpg']);
        $this->assertFalse($this->validator->validateReject([], $file));
    }
}

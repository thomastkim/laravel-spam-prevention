<?php

use Mockery as m;

use Kim\Defender\Laravel\DefenderHtmlGenerator;

class DefenderHtmlGeneratorTest extends PHPUnit_Framework_TestCase {

    protected $html;

	public function setUp()
	{
        $this->html = new DefenderHtmlGenerator;
	}

    public function tearDown()
    {
        m::close();
    }

    /** @test */
    public function it_should_throw_an_exception_if_the_argument_provides_no_token()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->html->generate([]);
    }

    /** @test */
    public function it_should_return_an_expression_if_the_argument_provides_a_valid_argument()
    {
        $this->assertInstanceOf('\Illuminate\View\Expression', $this->html->generate(['someToken']));
    }
}

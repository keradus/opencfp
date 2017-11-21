<?php

namespace OpenCFP\Test\Unit;

use OpenCFP\Environment;

/**
 * @covers \OpenCFP\Environment
 */
class EnvironmentTest extends \PHPUnit\Framework\TestCase
{
    /** @test */
    public function it_should_encapsulate_valid_environments()
    {
        $this->assertInstanceOf(Environment::class, Environment::production());

        $this->assertEquals('production', Environment::production());
        $this->assertEquals('development', Environment::development());
        $this->assertEquals('testing', Environment::testing());
    }

    /** @test */
    public function it_should_be_resolvable_from_environment_variable()
    {
        $_SERVER['CFP_ENV'] = 'testing';
        $this->assertEquals('testing', Environment::fromEnvironmentVariable());
    }

    /** @test */
    public function it_should_be_resolvable_from_string()
    {
        $this->assertEquals('testing', Environment::fromString('testing'));
    }

    /**
     * @test
     */
    public function it_fails_when_given_an_invalid_environment_string()
    {
        $this->expectException(\InvalidArgumentException::class);

        Environment::fromString('foo');
    }

    public function testEqualsReturnsFalseIfSlugIsDifferent()
    {
        $one = Environment::fromString('testing');
        $two = Environment::fromString('development');

        $this->assertFalse($one->equals($two));
    }

    public function testEqualsReturnsTrueIfSlugIsSame()
    {
        $one = Environment::fromString('testing');
        $two = Environment::fromString('testing');

        $this->assertTrue($one->equals($two));
    }
}
<?php

namespace OpenCFP\Test\Unit;

use OpenCFP\Environment;

/**
 * @covers \OpenCFP\Environment
 */
class EnvironmentTest extends \PHPUnit\Framework\TestCase
{
    public function testConstants()
    {
        $this->assertSame('production', Environment::TYPE_PRODUCTION);
        $this->assertSame('development', Environment::TYPE_DEVELOPMENT);
        $this->assertSame('testing', Environment::TYPE_TESTING);
    }
    
    public function testProductionReturnsEnvironment()
    {
        $environment = Environment::production();
        
        $this->assertInstanceOf(Environment::class, $environment);
        $this->assertTrue($environment->isProduction());
        $this->assertFalse($environment->isDevelopment());
        $this->assertFalse($environment->isTesting());
        $this->assertEquals(Environment::TYPE_PRODUCTION, $environment);
    }

    public function testDevelopmentReturnsEnvironment()
    {
        $environment = Environment::development();

        $this->assertInstanceOf(Environment::class, $environment);
        $this->assertFalse($environment->isProduction());
        $this->assertTrue($environment->isDevelopment());
        $this->assertFalse($environment->isTesting());
        $this->assertEquals(Environment::TYPE_DEVELOPMENT, $environment);
    }

    public function testTestingReturnsEnvironment()
    {
        $environment = Environment::testing();

        $this->assertInstanceOf(Environment::class, $environment);
        $this->assertFalse($environment->isProduction());
        $this->assertFalse($environment->isDevelopment());
        $this->assertTrue($environment->isTesting());
        $this->assertEquals(Environment::TYPE_TESTING, $environment);
    }

    /**
     * @test
     * @dataProvider providerEnvironment
     *
     * @param string $type
     */
    public function it_should_be_resolvable_from_environment_variable(string $type)
    {
        $_SERVER['CFP_ENV'] = $type;

        $environment = Environment::fromEnvironmentVariable();

        $this->assertInstanceOf(Environment::class, $environment);
        $this->assertEquals($type, $environment);
    }

    /**
     * @dataProvider providerEnvironment
     *
     * @param string $type
     */
    public function testFromServerReturnsEnvironment(string $type)
    {
        $environment = Environment::fromServer([
            'CFP_ENV' => $type,
        ]);

        $this->assertInstanceOf(Environment::class, $environment);
        $this->assertEquals($type, $environment);
    }

    /**
     * @test
     * @dataProvider providerEnvironment
     *
     * @param string $type
     */
    public function it_should_be_resolvable_from_string(string $type)
    {
        $environment = Environment::fromString($type);

        $this->assertInstanceOf(Environment::class, $environment);
        $this->assertEquals($type, $environment);
    }

    public function providerEnvironment(): \Generator
    {
        $types = [
            Environment::TYPE_DEVELOPMENT,
            Environment::TYPE_PRODUCTION,
            Environment::TYPE_TESTING,
        ];

        foreach ($types as $type) {
            yield $type => [
                $type,
            ];
        }
    }

    /**
     * @test
     */
    public function it_fails_when_given_an_invalid_environment_string()
    {
        $type = 'foo';

        $types = [
            Environment::TYPE_PRODUCTION,
            Environment::TYPE_DEVELOPMENT,
            Environment::TYPE_TESTING,
        ];

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            'Environment needs to be one of "%s"; got "%s" instead.',
            implode('", "', $types),
            $type
        ));

        Environment::fromString($type);
    }

    public function testEqualsReturnsFalseIfTypeIsDifferent()
    {
        $one = Environment::fromString(Environment::TYPE_TESTING);
        $two = Environment::fromString(Environment::TYPE_DEVELOPMENT);

        $this->assertFalse($one->equals($two));
    }

    public function testEqualsReturnsTrueIfTypeIsSame()
    {
        $one = Environment::fromString(Environment::TYPE_TESTING);
        $two = Environment::fromString(Environment::TYPE_TESTING);

        $this->assertTrue($one->equals($two));
    }
}

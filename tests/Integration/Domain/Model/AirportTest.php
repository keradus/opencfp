<?php

namespace OpenCFP\Test\Integration\Domain\Model;

use OpenCFP\Domain\Model\Airport;
use OpenCFP\Test\BaseTestCase;
use OpenCFP\Test\Helper\RefreshDatabase;

/**
 * @group db
 * @coversNothing
 */
class AirportTest extends BaseTestCase
{
    use RefreshDatabase;

    private $airports;

    protected function setUp()
    {
        parent::setUp();
        $this->airports = $this->getAirport();
    }

    /** @test */
    public function it_queries_airports_table_by_code()
    {
        $airport = $this->airports->withCode('AAC');

        $this->assertEquals('AAC', $airport->code);
        $this->assertEquals('Al Arish', $airport->name);
        $this->assertEquals('Egypt', $airport->country);
    }

    /**
     * @test
     */
    public function it_squawks_when_airport_is_not_found()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('not found');

        $this->airports->withCode('foobarbaz');
    }

    private function getAirport(): Airport
    {
        return new Airport;
    }
}

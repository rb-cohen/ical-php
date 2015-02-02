<?php

namespace IcalTest\Component;

use Ical\Component\Event;

class EventTest extends \PHPUnit_Framework_TestCase {

    public function testDefaultConstruct() {
        $event = new Event('test-1');
        $this->assertSame('test-1', $event->uid);
        $this->assertInstanceOf('DateTime', $event->dtstamp);
    }

    public function testBetween() {
        $start = new \DateTime('2015-01-01');
        $end = new \DateTime('2015-01-02');

        $event = new Event('test-1');
        $event->between($start, $end);

        $this->assertSame($start, $event->start);
        $this->assertSame($end, $event->end);
    }

    public function testOn() {
        $on = new \DateTime('2015-01-01');
        $end = new \DateTime('2015-01-02');

        $event = new Event('test-1');
        $event->on($on);

        $this->assertSame($on, $event->start);
        $this->assertEquals($end, $event->end);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testOnThrowsExceptionIfNotMidnight() {
        $on = new \DateTime('2015-01-01T00:00:01Z');

        $event = new Event('test-1');
        $event->on($on);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testToIcalExceptionWhenNoDateSet() {
        $event = new Event('test-1');
        $event->toIcal();
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testToIcalExceptionWhenNoEndDate() {
        $event = new Event('test-1');
        $event->start(new \DateTime('now'));
        $event->toIcal();
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testToIcalExceptionWhenNoStartDate() {
        $event = new Event('test-1');
        $event->end(new \DateTime('now'));
        $event->toIcal();
    }

    /**
     * @depends testBetween
     * @expectedException \RuntimeException
     */
    public function testToIcalExceptionWhenStartDateGreaterThanEndDate() {
        $event = new Event('test-1');
        $event->between(new \DateTime('2015-01-02'), new \DateTime('2015-01-01'));
        $event->toIcal();
    }

}

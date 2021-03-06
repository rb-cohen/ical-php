<?php

namespace IcalTest\Component;

use Ical\Component\Event;
use Ical\Property\DateTimeStamp;

class EventTest extends \PHPUnit_Framework_TestCase {

    public function testDefaultConstruct() {
        $event = new Event('test-1');
        $this->assertSame('test-1', $event->uid);
        $this->assertInstanceOf('DateTime', $event->dtstamp);
        $this->assertSame(DateTimeStamp::OUTPUT_UTC, $event->dateFormat);
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
        $this->assertTrue(($event->dateFormat & DateTimeStamp::OUTPUT_NOTIME) > 0);
    }

    /**
     * @depends testDefaultConstruct
     */
    public function testAllDay() {
        $event = new Event('test-1');

        $event->allDay(false);
        $this->assertSame(DateTimeStamp::OUTPUT_UTC, $event->dateFormat);

        $event->allDay(true);
        $this->assertSame(DateTimeStamp::OUTPUT_UTC | DateTimeStamp::OUTPUT_NOTIME, $event->dateFormat);

        $event->allDay(false);
        $this->assertSame(DateTimeStamp::OUTPUT_UTC, $event->dateFormat);
    }

    /**
     * @expectedException \Ical\Exception\RuntimeException
     */
    public function testOnThrowsExceptionIfNotMidnight() {
        $on = new \DateTime('2015-01-01T00:00:01Z');

        $event = new Event('test-1');
        $event->on($on);
    }

    /**
     * @expectedException \Ical\Exception\RuntimeException
     */
    public function testToIcalExceptionWhenNoDateSet() {
        $event = new Event('test-1');
        $event->toIcal();
    }

    /**
     * @expectedException \Ical\Exception\RuntimeException
     */
    public function testToIcalExceptionWhenNoEndDate() {
        $event = new Event('test-1');
        $event->start(new \DateTime('now'));
        $event->toIcal();
    }

    /**
     * @expectedException \Ical\Exception\RuntimeException
     */
    public function testToIcalExceptionWhenNoStartDate() {
        $event = new Event('test-1');
        $event->end(new \DateTime('now'));
        $event->toIcal();
    }

    /**
     * @depends testBetween
     * @expectedException \Ical\Exception\RuntimeException
     */
    public function testToIcalExceptionWhenStartDateGreaterThanEndDate() {
        $event = new Event('test-1');
        $event->between(new \DateTime('2015-01-02'), new \DateTime('2015-01-01'));
        $event->toIcal();
    }

    public function testToIcalBasic() {
        $event = new Event('test-1', new \DateTime('2015-01-01T00:00:00'));
        $event->between(new \DateTime('2015-01-02'), new \DateTime('2015-01-03'));
        $ical = $event->toIcal();

        $expected = file_get_contents(__DIR__ . '/../../mocks/component/event-basic.ical');
        $this->assertEquals($expected, $ical);
    }

    public function testToIcalDateFormatTimezone() {
        $event = new Event('test-1', new \DateTime('2015-01-01T00:00:00'));
        $event->between(new \DateTime('2015-01-02', new \DateTimeZone('America/New_York')), new \DateTime('2015-01-03', new \DateTimeZone('America/New_York')));
        $event->created(new \DateTime('2015-01-01'));
        $event->setDateFormat(DateTimeStamp::OUTPUT_TIMEZONE);
        $ical = $event->toIcal();

        $expected = file_get_contents(__DIR__ . '/../../mocks/component/event-timezone.ical');
        $this->assertEquals($expected, $ical);
    }

    public function testToIcalFull() {
        $event = new Event('test-1', new \DateTime('2015-01-01T00:00:00'));
        $event->between(new \DateTime('2015-01-02'), new \DateTime('2015-01-03'));
        $event->created(new \DateTime('2015-01-01'));
        $event->description('A test description');
        $event->location('Greenwich');
        $event->summary('A test summary');
        $ical = $event->toIcal();

        $expected = file_get_contents(__DIR__ . '/../../mocks/component/event-full.ical');
        $this->assertEquals($expected, $ical);
    }

}

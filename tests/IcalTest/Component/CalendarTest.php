<?php

namespace IcalTest\Component;

use Ical\Component\Calendar;

class CalendarTest extends \PHPUnit_Framework_TestCase {

    public function testDefaultConstruct() {
        $calendar = new Calendar('-//Ical-PHP//Test v1//EN');
        $ical = $calendar->toIcal();

        $expected = file_get_contents(__DIR__ . '/../../mocks/component/calendar-defaultConstruct.ical');
        $this->assertEquals($expected, $ical);
        $this->assertCount(0, $calendar->getComponents());
    }

    public function testVersionConstruct() {
        $calendar = new Calendar('-//Ical-PHP//Test v1//EN', '2.1');
        $ical = $calendar->toIcal();

        $expected = file_get_contents(__DIR__ . '/../../mocks/component/calendar-versionConstruct.ical');
        $this->assertEquals($expected, $ical);
    }

    public function testAddEvent() {
        $event = $this->getMockBuilder('Ical\Component\Event')
                ->disableOriginalConstructor()
                ->getMock();

        $calendar = new Calendar('-//Ical-PHP//Test v1//EN');
        $calendar->addEvent($event);

        $this->assertCount(1, $calendar->getComponents());
        $this->assertEquals(array($event), $calendar->getComponents());
    }

}

<?php

namespace IcalTest\Property;

use Ical\Property\DateTimeStamp;

class DateTimeStampTest extends \PHPUnit_Framework_TestCase {

    public function testConstruct() {
        $date = new \DateTime('2015-01-01');

        $property = new DateTimeStamp('DTSTAMP', $date);
        $this->assertEquals('DTSTAMP', $property->key);
        $this->assertSame($date, $property->value);
    }

    public function testConstuctFormatsKey() {
        $tests = array(
            'dtstamp' => 'DTSTAMP',
            ' dtstamp ' => 'DTSTAMP'
        );

        foreach ($tests as $key => $expected) {
            $property = new DateTimeStamp($key, new \DateTime('now'));
            $this->assertEquals($expected, $property->key);
        }
    }

    public function testToIcal() {
        $property = new DateTimeStamp('DTSTAMP', new \DateTime('2015-01-01'));
        $this->assertEquals('DTSTAMP:20150101T000000Z', $property->toIcal());
    }

    /**
     * @depends testToIcal
     */
    public function testToString() {
        $property = new DateTimeStamp('DTSTAMP', new \DateTime('2015-01-01'));
        $this->assertEquals($property->toIcal(), (string) $property);
    }

}

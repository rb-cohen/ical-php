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

    public function testGetUTCDate() {
        $est = new \DateTime('2015-01-01T12:00:00', new \DateTimeZone('EST'));
        $property = new DateTimeStamp('DTSTAMP', $est);
        $utc = $property->getUTCDate();

        $this->assertEquals(new \DateTime('2015-01-01T17:00:00'), $utc);
        $this->assertSame($est, $property->value);
    }

    /**
     * @depends testGetUTCDate
     */
    public function testGetStampDefault() {
        $est = new \DateTime('2015-01-01T12:00:00', new \DateTimeZone('EST'));
        $property = new DateTimeStamp('DTSTAMP', $est);
        $this->assertEquals('20150101T170000Z', $property->getStamp());
    }

    public function testGetStampUTC() {
        $est = new \DateTime('2015-01-01T12:00:00', new \DateTimeZone('EST'));
        $property = new DateTimeStamp('DTSTAMP', $est, DateTimeStamp::OUTPUT_UTC);
        $this->assertEquals('20150101T170000Z', $property->getStamp());
    }
    
    public function testGetStampUTCNoTime() {
        $est = new \DateTime('2015-01-01T22:00:00', new \DateTimeZone('EST'));
        $property = new DateTimeStamp('DTSTAMP', $est, DateTimeStamp::OUTPUT_UTC|DateTimeStamp::OUTPUT_NOTIME);
        $this->assertEquals('20150102', $property->getStamp());
    }

    public function testGetStampAmbiguous() {
        $est = new \DateTime('2015-01-01T12:00:00', new \DateTimeZone('EST'));
        $property = new DateTimeStamp('DTSTAMP', $est, DateTimeStamp::OUTPUT_AMBIGUOUS);
        $this->assertEquals('20150101T120000', $property->getStamp());
    }
    
    public function testGetStampAmbiguousNoTime() {
        $est = new \DateTime('2015-01-01T12:00:00', new \DateTimeZone('EST'));
        $property = new DateTimeStamp('DTSTAMP', $est, DateTimeStamp::OUTPUT_AMBIGUOUS|DateTimeStamp::OUTPUT_NOTIME);
        $this->assertEquals('20150101', $property->getStamp());
    }

    public function testGetStampTimezone() {
        $est = new \DateTime('2015-01-01T12:00:00', new \DateTimeZone('EST'));
        $property = new DateTimeStamp('DTSTAMP', $est, DateTimeStamp::OUTPUT_TIMEZONE);
        $this->assertEquals('20150101T120000', $property->getStamp());
    }
    
    public function testGetStampTimezoneNoTime() {
        $est = new \DateTime('2015-01-01T12:00:00', new \DateTimeZone('EST'));
        $property = new DateTimeStamp('DTSTAMP', $est, DateTimeStamp::OUTPUT_TIMEZONE|DateTimeStamp::OUTPUT_NOTIME);
        $this->assertEquals('20150101', $property->getStamp());
    }

    /**
     * @depends testGetStampDefault
     */
    public function testToIcal() {
        $property = new DateTimeStamp('DTSTAMP', new \DateTime('2015-01-01'));
        $this->assertEquals('DTSTAMP:20150101T000000Z', $property->toIcal());
    }
    
    /**
     * @depends testGetStampDefault
     */
    public function testToIcalNoTime() {
        $property = new DateTimeStamp('DTSTAMP', new \DateTime('2015-01-01'));
        $property->noTime(true);
        $this->assertEquals('DTSTAMP;VALUE=DATE:20150101', $property->toIcal());
    }

    /**
     * @depends testGetStampAmbiguous
     */
    public function testToIcalAmbiguous() {
        $est = new \DateTime('2015-01-01T12:00:00', new \DateTimeZone('America/New_York'));
        $property = new DateTimeStamp('DTSTAMP', $est, DateTimeStamp::OUTPUT_AMBIGUOUS);
        $this->assertEquals('DTSTAMP:20150101T120000', $property->toIcal());
    }
    
    /**
     * @depends testGetStampAmbiguous
     */
    public function testToIcalAmbiguousNoTime() {
        $est = new \DateTime('2015-01-01T12:00:00', new \DateTimeZone('America/New_York'));
        $property = new DateTimeStamp('DTSTAMP', $est, DateTimeStamp::OUTPUT_AMBIGUOUS);
        $property->noTime(true);
        $this->assertEquals('DTSTAMP;VALUE=DATE:20150101', $property->toIcal());
    }

    /**
     * @depends testGetStampAmbiguous
     */
    public function testToIcalTimezone() {
        $est = new \DateTime('2015-01-01T12:00:00', new \DateTimeZone('America/New_York'));
        $property = new DateTimeStamp('DTSTAMP', $est, DateTimeStamp::OUTPUT_TIMEZONE);
        $this->assertEquals('DTSTAMP;TZID=America/New_York:20150101T120000', $property->toIcal());
    }
    
    /**
     * @depends testGetStampAmbiguous
     */
    public function testToIcalTimezoneNoTime() {
        $est = new \DateTime('2015-01-01T12:00:00', new \DateTimeZone('America/New_York'));
        $property = new DateTimeStamp('DTSTAMP', $est, DateTimeStamp::OUTPUT_TIMEZONE|DateTimeStamp::OUTPUT_NOTIME);
        $this->assertEquals('DTSTAMP;TZID=America/New_York;VALUE=DATE:20150101', $property->toIcal());
    }

    /**
     * @depends testToIcal
     */
    public function testToString() {
        $property = new DateTimeStamp('DTSTAMP', new \DateTime('2015-01-01'));
        $this->assertEquals($property->toIcal(), (string) $property);
    }

}

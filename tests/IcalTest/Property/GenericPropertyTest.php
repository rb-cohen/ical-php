<?php

namespace IcalTest\Property;

use Ical\Property\Text;

class TextTest extends \PHPUnit_Framework_TestCase {

    public function testConstruct() {
        $property = new Text('BEGIN', 'VCALENDAR');
        $this->assertEquals('BEGIN', $property->key);
        $this->assertEquals('VCALENDAR', $property->value);
    }

    public function testConstuctFormatsKey() {
        $tests = array(
            'begin' => 'BEGIN',
            ' begin ' => 'BEGIN'
        );

        foreach ($tests as $key => $expected) {
            $property = new Text($key, null);
            $this->assertEquals($expected, $property->key);
        }
    }

    public function testToIcal() {
        $property = new Text('begin', 'VCALENDAR');
        $this->assertEquals('BEGIN:VCALENDAR', $property->toIcal());
    }

    public function testToString() {
        $property = new Text('begin', 'VCALENDAR');
        $this->assertEquals($property->toIcal(), (string) $property);
    }

}

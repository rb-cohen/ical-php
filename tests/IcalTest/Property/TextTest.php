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
        $property = new Text('BEGIN', 'VCALENDAR');
        $this->assertEquals('BEGIN:VCALENDAR', $property->toIcal());
    }

    /**
     * @depends testToIcal
     */
    public function testToString() {
        $property = new Text('BEGIN', 'VCALENDAR');
        $this->assertEquals($property->toIcal(), (string) $property);
    }

    public function testToIcalWillSplitLongValue() {
        $key = 'DESCRIPTION';
        $value = 'Any old description will do';

        $mock = $this->getMockBuilder('Ical\Property\Text')
                ->setMethods(array('icalSplit'))
                ->setConstructorArgs(array($key, $value))
                ->getMock();

        $mock->expects($this->once())
                ->method('icalSplit')
                ->with($value, $key)
                ->will($this->returnValue($value));

        $mock->toIcal();
    }

    public function testIcalSplit() {
        $key = 'DESCRIPTION';
        $description = "This is a very long description that needs breaking in to multiple lines because it is longer than 75 octets not only once but twice. Only once is not enough";
        $expected = "This is a very long description that needs breaking in to multi"
                . "\n\tple lines because it is longer than 75 octets not only once but twice. Onl"
                . "\n\ty once is not enough";

        $property = new Text($key, 'VCALENDAR');
        $this->assertEquals($expected, $property->icalSplit($description, $key));
    }

    /**
     * @depends testIcalSplit
     */
    public function testIcalSplitUtf8() {
        $key = 'DESCRIPTION';
        $description = "This is a buffer of text that we need to make long (och hör på den) for UTF chars split";
        $expected = "This is a buffer of text that we need to make long (och hör p"
                . "\n\tå den) for UTF chars split";

        $property = new Text($key, 'VCALENDAR');
        $this->assertEquals($expected, $property->icalSplit($description, $key));
    }

}

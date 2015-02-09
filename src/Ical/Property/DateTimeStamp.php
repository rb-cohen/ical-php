<?php

namespace Ical\Property;

use DateTime;
use DateTimeZone;
use Ical\Exception\RuntimeException;

class DateTimeStamp implements PropertyInterface {

    const OUTPUT_UTC = 1;
    const OUTPUT_TIMEZONE = 2;
    const OUTPUT_AMBIGUOUS = 4;
    const OUTPUT_NOTIME = 8;

    public $key;
    public $value;
    public $format;
    public $properties;

    public function __construct($key, DateTime $value, $format = self::OUTPUT_UTC) {
        $this->key = $this->formatKey($key);
        $this->value = $value;
        $this->format = $format;
    }

    public function formatKey($key) {
        return strtoupper(trim($key));
    }

    public function noTime($noTime = true) {
        if ($noTime) {
            $this->format |= DateTimeStamp::OUTPUT_NOTIME;
        } else {
            $this->format ^= DateTimeStamp::OUTPUT_NOTIME;
        }

        return $this;
    }

    public function toIcal() {
        if ($this->format & self::OUTPUT_TIMEZONE) {
            $this->properties['TZID'] = $this->value->getTimezone()->getName();
        }
        
        if ($this->format & self::OUTPUT_NOTIME) {
            $this->properties['VALUE'] = 'DATE';
        }

        return $this->key . $this->getPropertiesString() . ':' . $this->getStamp();
    }

    public function getStamp() {
        $noTime = (($this->format & self::OUTPUT_NOTIME) > 0);

        switch (true) {
            case ($this->format & self::OUTPUT_UTC):
                $format = ($noTime) ? 'Ymd' : 'Ymd\THis\Z';
                return $this->getUTCDate()->format($format);
            case ($this->format & self::OUTPUT_TIMEZONE):
            case ($this->format & self::OUTPUT_AMBIGUOUS):
                $format = ($noTime) ? 'Ymd' : 'Ymd\THis';
                return $this->value->format($format);
            default:
                throw new RuntimeException('Invalid output format');
        }
    }

    public function getUTCDate() {
        $utcDate = clone $this->value;
        $utcDate->setTimezone(new DateTimeZone('UTC'));
        return $utcDate;
    }

    public function setOutputUTC($bool) {
        $this->outputUTC = (bool) $bool;
        return $this;
    }

    public function __toString() {
        return $this->toIcal();
    }

    protected function getPropertiesString() {
        if (count($this->properties) > 0) {
            $pairs = array();
            foreach ($this->properties as $key => $value) {
                $pairs[] = $key . '=' . $value;
            }

            return ';' . implode(';', $pairs);
        }
    }

}

<?php

namespace Ical\Property;

use DateTime;
use DateTimeZone;
use Ical\Exception\RuntimeException;

class DateTimeStamp implements PropertyInterface {

    const OUTPUT_UTC = 'utc';
    const OUTPUT_TIMEZONE = 'timezone';
    const OUTPUT_AMBIGUOUS = 'ambiguous';

    public $key;
    public $value;
    public $format;

    public function __construct($key, DateTime $value, $format = self::OUTPUT_UTC) {
        $this->key = $this->formatKey($key);
        $this->value = $value;
        $this->format = $format;
    }

    public function formatKey($key) {
        return strtoupper(trim($key));
    }

    public function toIcal() {
        return $this->key . $this->getTimezoneIdentifier() . ':' . $this->getStamp();
    }

    public function getStamp() {
        switch ($this->format) {
            case self::OUTPUT_UTC:
                return $this->getUTCDate()->format('Ymd\THis\Z');
            case self::OUTPUT_TIMEZONE:
            case self::OUTPUT_AMBIGUOUS:
                return $this->value->format('Ymd\THis');
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

    protected function getTimezoneIdentifier() {
        switch ($this->format) {
            case self::OUTPUT_TIMEZONE:
                return ';TZID=' . $this->value->getTimezone()->getName();
            default:
                return null;
        }
    }

}

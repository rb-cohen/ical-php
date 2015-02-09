<?php

namespace Ical\Property;

use DateTime;
use DateTimeZone;

class DateTimeStamp implements PropertyInterface {

    public $key;
    public $value;

    public function __construct($key, DateTime $value) {
        $this->key = $this->formatKey($key);
        $this->value = $value;
    }

    public function formatKey($key) {
        return strtoupper(trim($key));
    }

    public function toIcal() {
        $stamp = $this->getUTCDate()->format('Ymd\THis\Z');
        return $this->key . ':' . $stamp;
    }

    public function getUTCDate() {
        $utcDate = clone $this->value;
        $utcDate->setTimezone(new DateTimeZone('UTC'));
        return $utcDate;
    }

    public function __toString() {
        return $this->toIcal();
    }

}

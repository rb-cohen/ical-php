<?php

namespace Ical;

use Ical\Component\Calendar;
use Ical\Output\OutputAwareInterface;
use Ical\Output\ToIcalInterface;

class Feed implements OutputAwareInterface, ToIcalInterface {

    use OutputAwareTrait;

    protected $calendars = array();

    public function __construct(array $calendars = array()) {
        foreach ($calendars as $calendar) {
            $this->addCalendar($calendar);
        }
    }

    public function addCalendar(Calendar $calendar) {
        $this->calendars[] = $calendar;
        return $this;
    }

    public function download($filename) {
        header('Content-Disposition: attachment; filename=' . $filename);
        $this->output();
    }

    public function output() {
        header('Content-type: text/calendar; charset=utf-8');
        echo $this->toIcal();
    }

    public function toIcal() {
        $output = $this->getOutput();

        $elements = array();
        foreach ($this->calendars as $calendar) {
            $calendar->setOutput($output);
            $elements[] = $calendar;
        }

        return $this->getOutput()->combine($elements);
    }

    public function __toString() {
        return $this->toIcal();
    }

}

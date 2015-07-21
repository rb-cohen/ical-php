<?php

namespace Ical\Component;

use Ical\Exception\RuntimeException;
use Ical\Output\OutputAwareTrait;
use Ical\Property\DateTimeStamp;
use Ical\Property\Text;
use Ical\Property\PropertyCollectionInterface;
use Ical\Property\PropertyCollectionTrait;
use DateTime;

class Event extends AbstractComponent implements ComponentInterface, ComponentCollectionInterface, PropertyCollectionInterface {

    use OutputAwareTrait,
        ComponentCollectionTrait,
        PropertyCollectionTrait;

    const STATUS_TENTATIVE = 'TENTATIVE';
    const STATUS_CONFIRMED = 'CONFIRMED';
    const STATUS_CANCELLED = 'CANCELLED';

    public $type = 'VEVENT';
    public $status = self::STATUS_CONFIRMED;
    public $dtstamp;
    public $uid;
    public $start;
    public $end;
    public $created;
    public $lastModified;
    public $summary;
    public $description;
    public $location;
    public $dateFormat = DateTimeStamp::OUTPUT_UTC;

    public function __construct($uid, DateTime $dtstamp = null) {
        $this->uid = $uid;

        if ($dtstamp === null) {
            $dtstamp = new DateTime('now');
        }
        $this->dtstamp = $dtstamp;
    }

    public function start(DateTime $date) {
        $this->start = $date;
        return $this;
    }

    public function end(DateTime $date) {
        $this->end = $date;
        return $this;
    }

    /**
     * Shortcut from start and end dates
     * 
     * @param \DateTime $start
     * @param \DateTime $end
     * @return \Ical\Component\Event
     */
    public function between(DateTime $start, DateTime $end) {
        $this->start($start)
                ->end($end);

        return $this;
    }

    /**
     * One day helper
     * Sets event to a single day
     * 
     * @param \DateTime $on
     * @return \Ical\Component\Event
     */
    public function on(DateTime $on) {
        if ($on->format('His') !== '000000') {
            throw new RuntimeException('One day events must start at midnight');
        }

        $fin = clone $on;
        $fin->add(new \DateInterval('P1D'));

        $this->start($on)
                ->end($fin)
                ->allDay(true);

        return $this;
    }

    public function allDay($allDay = true) {
        if ($allDay) {
            $this->dateFormat |= DateTimeStamp::OUTPUT_NOTIME;
        } else {
            $this->dateFormat &= ~DateTimeStamp::OUTPUT_NOTIME;
        }

        return $this;
    }

    public function created(DateTime $date) {
        $this->created = $date;
        return $this;
    }

    public function lastModified(DateTime $date){
        $this->lastModified = $date;
        return $this;
    }

    public function summary($summary) {
        $this->summary = $summary;
        return $this;
    }

    public function description($description) {
        $this->description = $description;
        return $this;
    }

    public function location($location) {
        $this->location = $location;
        return $this;
    }

    public function status($status) {
        $this->status = $status;
        return $this;
    }

    public function setDateFormat($dateFormat) {
        $this->dateFormat = $dateFormat;
        return $this;
    }

    protected function assemble() {
        if (null === $this->start || null === $this->end) {
            throw new RuntimeException('Start and end dates must be set on an event');
        }

        if ($this->start > $this->end) {
            throw new RuntimeException('Start date must be before end date');
        }

        $this->addProperty(new DateTimeStamp('DTSTAMP', $this->dtstamp));
        $this->addProperty(new Text('UID', $this->uid));
        $this->addProperty(new DateTimeStamp('DTSTART', $this->start, $this->dateFormat));
        $this->addProperty(new DateTimeStamp('DTEND', $this->end, $this->dateFormat));
        $this->addProperty(new Text('STATUS', $this->status));

        if (null !== $this->created) {
            $this->addProperty(new DateTimeStamp('CREATED', $this->created));
        }

        if (null !== $this->lastModified) {
            $this->addProperty(new DateTimeStamp('LAST-MODIFIED', $this->lastModified));
        }

        if (null !== $this->summary) {
            $this->addProperty(new Text('SUMMARY', $this->summary));
        }

        if (null !== $this->description) {
            $this->addProperty(new Text('DESCRIPTION', $this->description));
        }

        if (null !== $this->location) {
            $this->addProperty(new Text('LOCATION', $this->location));
        }

        if (DateTimeStamp::OUTPUT_NOTIME & $this->dateFormat) {
            $this->addProperty(new Text('TRANSP', 'TRANSPARENT'));
        }
    }

}

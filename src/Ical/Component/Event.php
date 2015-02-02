<?php

namespace Ical\Component;

use Ical\Output\OutputAwareTrait;
use Ical\Property\Text;
use Ical\Property\PropertyCollectionInterface;
use Ical\Property\PropertyCollectionTrait;

class Event extends AbstractComponent implements ComponentInterface, ComponentCollectionInterface, PropertyCollectionInterface {

    public $type = 'VEVENT';
    public $dtstamp;
    public $uid;

    use OutputAwareTrait,
        ComponentCollectionTrait,
        PropertyCollectionTrait;

    public function __construct(DateTime $created, $uid) {
        $this->dtstamp = $created;
        $this->uid = $uid;
    }

    protected function assemble() {
        $this->addProperty(new DateTimeStamp('DTSTAMP', $this->dtstamp));
        $this->addProperty(new Text('UID', $this->uid));
    }

}

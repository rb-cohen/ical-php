<?php

namespace Ical\Component;

use Ical\Output\OutputAwareTrait;
use Ical\Property\Text;
use Ical\Property\PropertyCollectionInterface;
use Ical\Property\PropertyCollectionTrait;

class Calendar extends AbstractComponent implements ComponentInterface, ComponentCollectionInterface, PropertyCollectionInterface {

    public $type = 'VCALENDAR';
    public $prodid;
    public $version;
    public $calscale = 'GREGORIAN';
    public $method = 'PUBLISH';

    use OutputAwareTrait,
        ComponentCollectionTrait,
        PropertyCollectionTrait;

    public function __construct($prodid, $version = "2.0") {
        $this->prodid = $prodid;
        $this->version = $version;
    }

    protected function assemble() {
        $this->addProperty(new Text('PRODID', $this->prodid));
        $this->addProperty(new Text('VERSION', $this->version));
        $this->addProperty(new Text('CALSCALE', $this->calscale));
        $this->addProperty(new Text('METHOD', $this->method));
    }

    public function addEvent(Event $event) {
        return $this->addComponent($event);
    }

}

<?php

namespace Ical\Component;

use Ical\Property\PropertyCollectionInterface;
use Ical\Property\Text;

abstract class AbstractComponent implements ComponentInterface {

    abstract protected function assemble();

    public function toIcal() {
        $this->assemble();

        $elements = array();

        $elements[] = new Text('BEGIN', $this->type);

        if ($this instanceof PropertyCollectionInterface) {
            $this->addElementsToArray($elements, $this->getProperties());
        }

        if ($this instanceof ComponentCollectionInterface) {
            $this->addElementsToArray($elements, $this->getComponents());
        }

        $elements[] = new Text('END', $this->type);

        return $this->getOutput()->combine($elements);
    }

    protected function addElementsToArray(array &$array, array $elements) {
        array_map(function($property) use (&$array) {
            $array[] = $property;
        }, $elements);

        return $this;
    }

}

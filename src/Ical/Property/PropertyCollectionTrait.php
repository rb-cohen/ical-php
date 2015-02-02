<?php

namespace Ical\Property;

trait PropertyCollectionTrait {

    protected $properties = array();

    public function addProperties(array $properties) {
        foreach ($properties as $property) {
            $this->addProperty($property);
        }

        return $this;
    }

    public function addProperty(PropertyInterface $property) {
        $this->properties[] = $property;
        return $this;
    }

    public function getProperties() {
        return $this->properties;
    }

}

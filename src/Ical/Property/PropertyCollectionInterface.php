<?php

namespace Ical\Property;

interface PropertyCollectionInterface {

    public function addProperties(array $properties);

    public function addProperty(PropertyInterface $property);

    public function getProperties();
}

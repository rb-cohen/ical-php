<?php

namespace Ical\Property;

class Text implements PropertyInterface {

    public $key;
    public $value;

    public function __construct($key, $value) {
        $this->key = $this->formatKey($key);
        $this->value = $value;
    }

    public function formatKey($key) {
        return strtoupper(trim($key));
    }

    public function toIcal() {
        return $this->key . ':' . $this->value;
    }

    public function __toString() {
        return $this->toIcal();
    }

}

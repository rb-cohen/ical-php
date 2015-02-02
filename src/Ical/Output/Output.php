<?php

namespace Ical\Output;

class Output {

    public $eol = "\n";

    public function combine(array $elements) {
        $ical = array_map(function(ToIcalInterface $element) {
            return $element->toIcal();
        }, $elements);

        return implode($this->eol, $ical);
    }

}

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
        $cleanValue = $this->icalParse($this->value);
        return $this->key . ':' . $this->icalSplit($this->value, $this->key);
    }

    public function __toString() {
        return $this->toIcal();
    }

    public function icalParse($value) {
        $value = str_replace('"', '\"', $value);
        $value = str_replace("\\", "\\\\", $value);
        $value = str_replace(",", "\,", $value);
        $value = str_replace(":", "\:", $value);
        $value = str_replace(";", "\;", $value);

        return $value;
    }

    public function icalSplit($string, $key = null) {
        $value = trim($string);
        $value = preg_replace('/\n+/', ' ', $value);
        $value = preg_replace('/\s{2,}/', ' ', $value);

        $preamble_len = ($key === null) ? 0 : strlen($key) + 1;

        $lines = array();
        while (strlen($value) > (75 - $preamble_len)) {
            $space = (75 - $preamble_len);
            $line = mb_strcut($value, 0, $space, 'UTF8');
            $value = mb_substr($value, mb_strlen($line), null);

            $lines[] = $line;
            $preamble_len = 1; // the \t character
        }

        if (strlen($value) > 0) {
            $lines[] = $value;
        }

        return join($lines, "\n\t");
    }

}
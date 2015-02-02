<?php

namespace Ical\Output;

trait OutputAwareTrait {

    protected $output;

    public function setOutput(Output $output) {
        $this->output = $output;
    }

    public function getOutput() {
        if (null === $this->output) {
            $this->output = new Output();
        }

        return $this->output;
    }

}

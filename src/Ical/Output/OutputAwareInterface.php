<?php

namespace Ical\Output;

interface OutputAwareInterface {

    public function setOutput(Output $output);

    public function getOutput();
}

<?php

namespace Ical\Component;

interface ComponentCollectionInterface {

    public function addComponents(array $components);

    public function addComponent(ComponentInterface $component);

    public function getComponents();
}

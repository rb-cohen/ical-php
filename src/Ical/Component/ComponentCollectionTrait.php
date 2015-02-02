<?php

namespace Ical\Component;

trait ComponentCollectionTrait {

    protected $components = array();

    public function addComponents(array $components) {
        foreach ($components as $component) {
            $this->addComponent($component);
        }

        return $this;
    }

    public function addComponent(ComponentInterface $component) {
        $this->components[] = $component;
        return $this;
    }

    public function getComponents() {
        return $this->components;
    }

}

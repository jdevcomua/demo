<?php

namespace App\Helpers;

trait ProcessedCache {

    public function save(array $options = [])
    {
        // Clear cache if new model created or changed processed attribute
        if (!$this->exists || ($this->isDirty() && array_key_exists('processed', $this->getDirty()))) {
            \Cache::tags((new \ReflectionClass($this))->getShortName())->flush();
        }

        \Log::debug('flush cache for ' . (new \ReflectionClass($this))->getShortName());

        return parent::save($options);
    }

}
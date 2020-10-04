<?php

namespace BristolSU\Support\Translation\Locale;

class DetectionStrategyStore
{

    protected array $strategies = [];

    protected array $prepend = [];

    protected array $append = [];

    public function register(string $className)
    {
        if(!array_key_exists($className, $this->strategies)) {
            $this->strategies[] = $className;
        }
    }

    public function registerLast(string $className)
    {
        if(!array_key_exists($className, $this->strategies)) {
            $this->prepend[] = $className;
        }
    }

    public function registerFirst(string $className)
    {
        if(!array_key_exists($className, $this->strategies)) {
            $this->append[] = $className;
        }
    }

    public function all(): array
    {
        return array_merge($this->append, $this->strategies, $this->prepend);
    }

}

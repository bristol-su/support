<?php

namespace BristolSU\Support\Connection;

use FormSchema\Transformers\VFGTransformer;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class RegisteredConnector implements Arrayable, Jsonable
{

    /**
     * The name of the connector
     * @var string
     */
    private $name;

    /**
     * A description for the connector
     * 
     * @var string
     */
    private $description;

    /**
     * The service of the connector
     * 
     * @var string
     */
    private $service;

    /**
     * The alias of the connector
     * 
     * @var string
     */
    private $alias;

    /**
     * The class of the connector
     * 
     * @var string
     */
    private $connector;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getService(): string
    {
        return $this->service;
    }

    /**
     * @param string $service
     */
    public function setService(string $service): void
    {
        $this->service = $service;
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @param string $alias
     */
    public function setAlias(string $alias): void
    {
        $this->alias = $alias;
    }

    /**
     * @return string
     */
    public function getConnector(): string
    {
        return $this->connector;
    }

    /**
     * @param string $connector
     */
    public function setConnector(string $connector): void
    {
        $this->connector = $connector;
    }

    public function toArray()
    {
        return [
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'service' => $this->getService(),
            'alias' => $this->getAlias(),
            'settings' => (new VFGTransformer)->transformToArray($this->getConnector()::settingsSchema())
        ];
    }

    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    public function __toString(): string
    {
        return $this->toJson();
    }
    
}
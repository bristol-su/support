<?php

namespace BristolSU\Support\Connection;

use FormSchema\Transformers\VFGTransformer;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

/**
 * A class to hold a connector and its metadata, and gracefully cast to json or array.
 */
class RegisteredConnector implements Arrayable, Jsonable
{
    /**
     * The name of the connector.
     *
     * @var string
     */
    private $name;

    /**
     * A description for the connector.
     *
     * @var string
     */
    private $description;

    /**
     * The service of the connector.
     *
     * @var string
     */
    private $service;

    /**
     * The alias of the connector.
     *
     * @var string
     */
    private $alias;

    /**
     * The class name of the connector.
     *
     * @var string
     */
    private $connector;

    /**
     * Get the name of the connector.
     *
     * @return string Name of the connector
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the name of the connector.
     *
     * @param string $name Name of the connector
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get the description of the connector.
     *
     * @return string Description of the connector
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set the description of the connector.
     *
     * @param string $description Description of the connector
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * Get the service the connector supplies.
     *
     * @return string Service the connector supplies
     */
    public function getService(): string
    {
        return $this->service;
    }

    /**
     * Set the service the connector supplies.
     *
     * @param string $service Service the connector supplies
     */
    public function setService(string $service): void
    {
        $this->service = $service;
    }

    /**
     * Get the alias of the connector.
     *
     * @return string Alias of the connector
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * Set the alias of the connector.
     *
     * @param string $alias Alias of the connector
     */
    public function setAlias(string $alias): void
    {
        $this->alias = $alias;
    }

    /**
     * Get the connector class name.
     *
     * @return string Class name of the connector
     */
    public function getConnector(): string
    {
        return $this->connector;
    }

    /**
     * Set the class name.
     *
     * @param string $connector Class name of the connector
     */
    public function setConnector(string $connector): void
    {
        $this->connector = $connector;
    }

    /**
     * Cast the registered connector to an array.
     *
     * @return array Representation of the registered connector as an array
     */
    public function toArray()
    {
        return [
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'service' => $this->getService(),
            'alias' => $this->getAlias(),
            'settings' => (new VFGTransformer())->transformToArray($this->getConnector()::settingsSchema())
        ];
    }

    /**
     * Cast the registered connector to json.
     *
     * @param int $options Options for json encoding
     *
     * @return string Representation of the registered connector as json
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Cast the registered connector to a string.
     *
     * @return string Representation of the registered connector as json (a string)
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}

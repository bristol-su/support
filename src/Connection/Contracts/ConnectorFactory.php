<?php


namespace BristolSU\Support\Connection\Contracts;


use BristolSU\Support\Connection\Connection;

interface ConnectorFactory
{

    public function createFromConnection(Connection $connection): Connector;

}
<?php

namespace BristolSU\Support\Connection\Contracts;

interface ServiceRequest
{

    public function required(string $alias, array $services = []);

    public function optional(string $alias, array $services = []);

    public function getRequired(string $alias);

    public function getOptional(string $alias);

    public function getAllRequired();

    public function getAllOptional();

}
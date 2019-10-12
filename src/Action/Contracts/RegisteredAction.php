<?php

namespace BristolSU\Support\Action\Contracts;

interface RegisteredAction
{
    public function setName($name);

    public function getName();

    public function setDescription($description);

    public function getDescription();

    public function setClassName($className);

    public function getClassName();

    public function toArray();

    public function toJson($options = 0);
}
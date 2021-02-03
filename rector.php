<?php

declare(strict_types=1);

use BristolSU\CodeStyle\Rector\Rules;
use Rector\Core\Configuration\Option;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {

    $parameters = $containerConfigurator->parameters();

    // paths to refactor; solid alternative to CLI arguments
    $parameters->set(Option::PATHS, [
        __DIR__ . '/src'
    ]);

    $rules = new Rules();
    $services = $containerConfigurator->services();
    $rules->setRules($services);

};

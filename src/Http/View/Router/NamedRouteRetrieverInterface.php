<?php


namespace BristolSU\Support\Http\View\Router;

interface NamedRouteRetrieverInterface
{
    public function all(): array;

    public function currentRouteName(): ?string;
}

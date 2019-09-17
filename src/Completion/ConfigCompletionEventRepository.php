<?php


namespace BristolSU\Support\Completion;


use BristolSU\Support\Completion\Contracts\CompletionEventRepository;
use Illuminate\Config\Repository;

class ConfigCompletionEventRepository implements CompletionEventRepository
{
    /**
     * @var Repository
     */
    private $config;

    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    public function allForModule(string $alias)
    {
        return $this->config->get($alias.'.completion');
    }
}

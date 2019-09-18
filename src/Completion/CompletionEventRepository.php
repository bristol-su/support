<?php


namespace BristolSU\Support\Completion;


use BristolSU\Support\Completion\Contracts\CompletionEventManager as CompletionEventManagerContract;
use BristolSU\Support\Completion\Contracts\CompletionEventRepository as CompletionEventRepositoryContract;
use Illuminate\Config\Repository;

class CompletionEventRepository implements CompletionEventRepositoryContract
{
    /**
     * @var CompletionEventManagerContract
     */
    private $manager;

    public function __construct(CompletionEventManagerContract $manager)
    {
        $this->manager = $manager;
    }

    public function allForModule(string $alias)
    {
        return $this->manager->allForModule($alias);
    }
}

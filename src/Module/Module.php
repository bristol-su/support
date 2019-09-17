<?php

namespace BristolSU\Support\Module;

use BristolSU\Support\Completion\Contracts\CompletionEventRepository;
use BristolSU\Support\Module\Contracts\Module as ModuleContract;
use BristolSU\Support\Permissions\Contracts\PermissionRepository;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Support\Arrayable;

class Module implements ModuleContract
{

    private $alias;

    public $completion;
    /**
     * @var ConfigRepository
     */
    private $config;
    /**
     * @var PermissionRepository
     */
    private $permissionRepository;

    public function __construct($alias, ConfigRepository $config, CompletionEventRepository $completion, PermissionRepository $permissionRepository)
    {
        $this->alias = $alias;
        $this->config = $config;
        $this->completion = $completion;
        $this->permissionRepository = $permissionRepository;
    }

    public function alias()
    {
        return $this->alias;
    }

    public function toArray()
    {
        // TODO Refactor config out

        return array_merge(
            $this->config->get($this->alias, []),
            [
                'alias' => $this->alias,
                'completion' => $this->completion->allForModule($this->alias),
                'permissions' => $this->permissionRepository->forModule($this->alias)
            ]
        );

    }

}

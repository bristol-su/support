<?php


namespace BristolSU\Support\Permissions\Testers;


use BristolSU\Support\Permissions\Contracts\PermissionRepository;
use BristolSU\Support\Permissions\Contracts\Testers\Tester;

class CheckPermissionExists extends Tester
{

    /**
     * @var PermissionRepository
     */
    private $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function can(string $ability): ?bool
    {
        try {
            $this->permissionRepository->get($ability);
        } catch (\Exception $e) {
            return false;
        }
        return parent::next($ability);
    }
}

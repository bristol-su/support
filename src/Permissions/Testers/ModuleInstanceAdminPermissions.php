<?php


namespace BristolSU\Support\Permissions\Testers;


use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Permissions\Contracts\Testers\Tester;
use Illuminate\Contracts\Container\Container;
use BristolSU\Support\Control\Contracts\Models\Group;
use BristolSU\Support\Control\Contracts\Models\Role;
use BristolSU\Support\Control\Contracts\Models\User;

/**
 * Class ModuleInstanceAdminPermissions
 * @package BristolSU\Support\Permissions\Testers
 */
class ModuleInstanceAdminPermissions extends Tester
{

    /**
     * @var Container
     */
    private $app;
    /**
     * @var LogicTester
     */
    private $logicTester;
    /**
     * @var Authentication
     */
    private $authentication;

    /**
     * ModuleInstanceAdminPermissions constructor.
     * @param Container $app
     * @param LogicTester $logicTester
     * @param Authentication $authentication
     */
    public function __construct(Container $app, LogicTester $logicTester, Authentication $authentication)
    {
        $this->app = $app;
        $this->logicTester = $logicTester;
        $this->authentication = $authentication;
    }

    /**
     * @param string $ability
     * @return bool|null
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function can(string $ability, ?User $user, ?Group $group, ?Role $role): ?bool
    {
        $moduleInstance = $this->app->make(ModuleInstance::class);
        if($moduleInstance->exists === false){
            return parent::next($ability);
        }
        $adminPermissions = $moduleInstance->moduleInstancePermissions->admin_permissions;
        if(!array_key_exists($ability, $adminPermissions)) {
            return parent::next($ability);
        }
        $logic = Logic::find($adminPermissions[$ability]);

        if($logic === null) {
            return parent::next($ability);
        }

        return $this->logicTester->evaluate($logic, $this->authentication->getUser(), $this->authentication->getGroup(), $this->authentication->getRole());
    }
}

<?php


namespace BristolSU\Support\Permissions\Testers;


use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Permissions\Contracts\Testers\Tester;
use Illuminate\Contracts\Container\Container;

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

    public function __construct(Container $app, LogicTester $logicTester)
    {
        $this->app = $app;
        $this->logicTester = $logicTester;
    }

    public function can(string $ability): ?bool
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

        return $this->logicTester->evaluate($logic);
    }
}

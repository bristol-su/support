<?php


namespace BristolSU\Support\Permissions\Testers;


use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Permissions\Contracts\Testers\Tester;
use Illuminate\Contracts\Container\Container;

/**
 * Class ModuleInstanceUserPermissions
 * @package BristolSU\Support\Permissions\Testers
 */
class ModuleInstanceUserPermissions extends Tester
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
     * ModuleInstanceUserPermissions constructor.
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
    public function can(string $ability): ?bool
    {
        $moduleInstance = $this->app->make(ModuleInstance::class);
        if($moduleInstance->exists === false){
            return parent::next($ability);
        }
        $participantPermissions = $moduleInstance->moduleInstancePermissions->participant_permissions;
        if(!array_key_exists($ability, $participantPermissions)) {
            return parent::next($ability);
        }
        $logic = Logic::find($participantPermissions[$ability]);

        if($logic === null) {
            return parent::next($ability);
        }

        return $this->logicTester->evaluate($logic, $this->authentication->getUser(), $this->authentication->getGroup(), $this->authentication->getRole());
    }
}

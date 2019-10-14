<?php


namespace BristolSU\Support\Permissions\Testers;


use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Permissions\Contracts\Testers\Tester;
use BristolSU\Support\Permissions\Models\ModelPermission;

class SystemLogicPermission extends Tester
{

    /**
     * @var LogicTester
     */
    private $logicTester;
    /**
     * @var Authentication
     */
    private $authentication;

    public function __construct(LogicTester $logicTester, Authentication $authentication)
    {
        $this->logicTester = $logicTester;
        $this->authentication = $authentication;
    }

    public function can(string $ability): ?bool
    {
        $permissions = ModelPermission::logic()->orderBy('created_at', 'ASC')->get();
        foreach($permissions as $permission) {
            if($this->logicTester->evaluate(Logic::findOrFail($permission->model_id), $this->authentication->getUser(), $this->authentication->getGroup(), $this->authentication->getRole())) {
                return $permission->result;
            }
        }
        return parent::next($ability);
    }
}

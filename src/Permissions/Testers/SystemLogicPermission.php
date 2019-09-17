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
     * @var Authentication
     */
    private $logicTester;

    public function __construct(LogicTester $logicTester)
    {
        $this->logicTester = $logicTester;
    }

    public function can(string $ability): ?bool
    {
        $permissions = ModelPermission::logic()->orderBy('created_at', 'ASC')->get();
        foreach($permissions as $permission) {
            if($this->logicTester->evaluate(Logic::findOrFail($permission->model_id))) {
                return $permission->result;
            }
        }
        return parent::next($ability);
    }
}

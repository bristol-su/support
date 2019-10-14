<?php


namespace BristolSU\Support\Filters\Filters;


use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Control\Contracts\Repositories\Role;
use BristolSU\Support\Control\Contracts\Repositories\Position;
use BristolSU\Support\Control\Contracts\Repositories\Role as RoleRepository;
use BristolSU\Support\Filters\Contracts\Filters\RoleFilter;

class RoleHasPosition extends RoleFilter
{
    /**
     * @var RoleRepository
     */
    private $positionRepository;
    /**
     * @var Role
     */
    private $roleRepository;

    public function __construct(Position $positionRepository, Role $roleRepository)
    {
        $this->positionRepository = $positionRepository;
        $this->roleRepository = $roleRepository;
    }

    public function evaluate($settings): bool
    {
        if($this->model()->position_id === (int)$settings['position']) {
            return true;
        }
        return false;
    }

    public function options(): array
    {
        $positions = $this->positionRepository->all();
        $options = ['position' => []];
        foreach($positions as $position) {
            $options['position'][$position->id] = $position->name;
        }
        return $options;
    }

    public function name()
    {
        return 'Role has a position';
    }

    public function description()
    {
        return 'Returns true if a role has a specific position';
    }

    public function alias()
    {
        return 'role_has_position';
    }

    public function audience($settings)
    {
        return $this->roleRepository->all()->filter(function($role) use ($settings) {
            return $role->position_id === (int)$settings['position'];
        });
    }


}

<?php


namespace BristolSU\Support\Filters\Filters;


use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Control\Contracts\Repositories\Role;
use BristolSU\Support\Control\Contracts\Repositories\Position;
use BristolSU\Support\Control\Contracts\Repositories\Role as RoleRepository;
use BristolSU\Support\Filters\Contracts\Filters\RoleFilter;

/**
 * Class RoleHasPosition
 * @package BristolSU\Support\Filters\Filters
 */
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

    /**
     * RoleHasPosition constructor.
     * @param Position $positionRepository
     * @param RoleRepository $roleRepository
     */
    public function __construct(Position $positionRepository, Role $roleRepository)
    {
        $this->positionRepository = $positionRepository;
        $this->roleRepository = $roleRepository;
    }

    /**
     * @param string $settings
     * @return bool
     */
    public function evaluate($settings): bool
    {
        if($this->model()->position_id === (int)$settings['position']) {
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    public function options(): array
    {
        $positions = $this->positionRepository->all();
        $options = ['position' => []];
        foreach($positions as $position) {
            $options['position'][$position->id] = $position->name;
        }
        return $options;
    }

    /**
     * @return mixed|string
     */
    public function name()
    {
        return 'Role has a position';
    }

    /**
     * @return mixed|string
     */
    public function description()
    {
        return 'Returns true if a role has a specific position';
    }

    /**
     * @return mixed|string
     */
    public function alias()
    {
        return 'role_has_position';
    }

    /**
     * @param $settings
     * @return \Illuminate\Support\Collection|mixed
     */
    public function audience($settings)
    {
        return $this->roleRepository->all()->filter(function($role) use ($settings) {
            return $role->position_id === (int)$settings['position'];
        });
    }


}

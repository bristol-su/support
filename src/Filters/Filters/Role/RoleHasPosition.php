<?php


namespace BristolSU\Support\Filters\Filters\Role;


use BristolSU\ControlDB\Contracts\Repositories\Position;
use BristolSU\ControlDB\Contracts\Repositories\Position as PositionRepository;
use BristolSU\Support\Filters\Contracts\Filters\RoleFilter;

/**
 * Does the role have the specified position?
 */
class RoleHasPosition extends RoleFilter
{
    /**
     * Holds the position repository
     * 
     * @var PositionRepository
     */
    private $positionRepository;

    /**
     * @param Position $positionRepository Position repository 
     */
    public function __construct(Position $positionRepository)
    {
        $this->positionRepository = $positionRepository;
    }

    /**
     * Does the role have the given position ID?
     * 
     * @param string $settings Contain the position id as 'position'
     * @return bool
     */
    public function evaluate($settings): bool
    {
        if($this->role()->positionId() === (int)$settings['position']) {
            return true;
        }
        return false;
    }

    /**
     * Register the position option
     * 
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
     * Return the filter name
     * 
     * @return string Filter name
     */
    public function name()
    {
        return 'Role has a position';
    }

    /**
     * Return the filter description
     * 
     * @return string Filter description
     */
    public function description()
    {
        return 'Returns true if a role has a specific position';
    }

    /**
     * Returh the filter alias
     * 
     * @return string Filter alias
     */
    public function alias()
    {
        return 'role_has_position';
    }

}

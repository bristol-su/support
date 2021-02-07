<?php


namespace BristolSU\Support\Filters\Filters\Role;

use BristolSU\ControlDB\Contracts\Repositories\Position;
use BristolSU\ControlDB\Contracts\Repositories\Position as PositionRepository;
use BristolSU\Support\Filters\Contracts\Filters\RoleFilter;
use FormSchema\Schema\Form;

/**
 * Does the role have the specified position?
 */
class RoleHasPosition extends RoleFilter
{
    /**
     * Holds the position repository.
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
        if ($this->role()->positionId() === (int) $settings['position']) {
            return true;
        }

        return false;
    }

    /**
     * Get possible options as an array.
     *
     * You should return a form schema which represents the available options for the filter
     *
     * @throws \Exception
     * @return Form Options
     *
     */
    public function options(): Form
    {
        $positions = $this->positionRepository->all();
        $values = [];
        foreach ($positions as $position) {
            $values[] = [
                'id' => $position->id(),
                'name' => $position->data()->name(),
            ];
        }

        return \FormSchema\Generator\Form::make()->withField(
            \FormSchema\Generator\Field::select('position')->values($values)->label('Position')
                ->required(true)
        )->getSchema();
    }

    /**
     * Return the filter name.
     *
     * @return string Filter name
     */
    public function name()
    {
        return 'Role has a position';
    }

    /**
     * Return the filter description.
     *
     * @return string Filter description
     */
    public function description()
    {
        return 'Returns true if a role has a specific position';
    }

    /**
     * Returh the filter alias.
     *
     * @return string Filter alias
     */
    public function alias()
    {
        return 'role_has_position';
    }
}

<?php


namespace BristolSU\Support\Filters\Filters\Role;

use BristolSU\ControlDB\Contracts\Models\Position as PositionModel;
use BristolSU\ControlDB\Contracts\Repositories\Position;
use BristolSU\ControlDB\Contracts\Repositories\Position as PositionRepository;
use BristolSU\ControlDB\Events\Role\RoleUpdated;
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
     * @param array $settings Contain the position id as 'position'
     * @return bool
     */
    public function evaluate(array $settings): bool
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
        $field = \FormSchema\Generator\Field::select('position')->setLabel('Position')->setRequired(true);
        $this->positionRepository->all()->each(fn(PositionModel $position) => $field->withOption($position->id(), $position->data()->name()));
        return \FormSchema\Generator\Form::make()->withField($field)->getSchema();
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

    public static function clearOn(): array
    {
        return [
            RoleUpdated::class => fn(RoleUpdated $event) => array_key_exists('position_id', $event->updatedData) ? $event->role->id() : false
        ];
    }
}

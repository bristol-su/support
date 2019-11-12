<?php


namespace BristolSU\Support\Filters\Contracts\Filters;

use BristolSU\Support\Control\Contracts\Models\Group;
use BristolSU\Support\Control\Contracts\Repositories\Group as GroupRepository;

/**
 * Class GroupFilter
 * @package BristolSU\Support\Filters\Contracts\Filters
 */
abstract class GroupFilter extends Filter
{

    /**
     * @var
     */
    private $group;

    /**
     * @param $model
     * @return mixed|void
     * @throws \Exception
     */
    public function setModel($model)
    {
        if(!($model instanceof Group)) {
            throw new \Exception(
                sprintf('Cannot pass a class of type [%s] to a group filter', get_class($model))
            );
        }
        $this->group = $model;
    }

    /**
     * @return bool
     */
    public function hasModel(): bool
    {
        return $this->group !== null;
    }

    /**
     * @return mixed
     */
    public function model()
    {
        return $this->group;
    }

    public function for()
    {
        return 'group';
    }
    
    public function audience($settings)
    {
        $audience = [];
        $groups = app()->make(GroupRepository::class)->all();
        foreach($groups as $group) {
            $this->setModel($group);
            if($this->evaluate($settings)) {
                $audience[] = $group;
            }
        }
        return $audience;
    }

}

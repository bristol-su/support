<?php

namespace BristolSU\Support\Filters\Filters\Group;

use BristolSU\ControlDB\Contracts\Repositories\Group as GroupRepository;
use BristolSU\ControlDB\Events\DataGroup\DataGroupUpdated;
use BristolSU\Support\Filters\Contracts\Filters\GroupFilter;
use FormSchema\Generator\Field;
use FormSchema\Schema\Form;

/**
 * Test the group name matches.
 */
class GroupNameIs extends GroupFilter
{
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
        return \FormSchema\Generator\Form::make()->withField(
            Field::textInput('Group Name')->setLabel('Group Name')
                ->setRequired(true)->setValue('Full name of the group')
        )->getSchema();
    }

    /**
     * Test if the group has the given name.
     *
     * @param array $settings [ 'Group Name' => 'name' ]
     *
     * @return bool
     */
    public function evaluate(array $settings): bool
    {
        return strtoupper($this->group()->data()->name()) === strtoupper($settings['Group Name']);
    }

    /**
     * Get the filter name.
     *
     * @return string Name
     */
    public function name()
    {
        return 'Group name is exactly';
    }

    /**
     * Filter description.
     *
     * @return string Description
     */
    public function description()
    {
        return 'Group name exactly matches the name given';
    }

    /**
     * Filter alias.
     *
     * @return string Alias
     */
    public function alias()
    {
        return 'group_name_is';
    }

    public static function clearOn(): array
    {
        return [
            DataGroupUpdated::class => fn(DataGroupUpdated $event) => app(GroupRepository::class)->getByDataProviderId($event->dataGroup->id())->id()
        ];
    }
}

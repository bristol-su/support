<?php


namespace BristolSU\Support\Filters\Filters\Group;

use BristolSU\ControlDB\Contracts\Repositories\Tags\GroupTag as GroupTagRepositoryContract;
use BristolSU\ControlDB\Contracts\Models\Tags\GroupTag;
use BristolSU\Support\Filters\Contracts\Filters\GroupFilter;
use FormSchema\Schema\Form;

/**
 * Is the group tagged with a tag?
 */
class GroupTagged extends GroupFilter
{
    /**
     * Group Tag Repository, for retrieving all tags as options.
     *
     * @var GroupTagRepositoryContract
     */
    private $groupTagRepository;

    /**
     * @param GroupTagRepositoryContract $groupTagRepository Group tag repository
     */
    public function __construct(GroupTagRepositoryContract $groupTagRepository)
    {
        $this->groupTagRepository = $groupTagRepository;
    }

    /**
     * See if the group is tagged.
     *
     * @param string $settings [ 'tag' => 'full.reference' ]
     *
     * @return bool
     */
    public function evaluate($settings): bool
    {
        try {
            $tags = $this->group()->tags();
        } catch (\Exception $e) {
            return false;
        }
        foreach ($tags as $tag) {
            if ($tag->fullReference() === $settings['tag']) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get all group tags as a select list.
     *
     * You should return a form schema which represents the available options for the filter
     *
     * @throws \Exception
     * @return Form Options
     *
     */
    public function options(): Form
    {
        $field = \FormSchema\Generator\Field::select('tag')->setLabel('Group Name')->setRequired(true);
        $this->groupTagRepository->all()->each(fn(GroupTag $tag) => $field->withOption($tag->fullReference(), sprintf('%s (%s)', $tag->name(), $tag->fullReference()), $tag->category()->name()));
        return \FormSchema\Generator\Form::make()->withField($field)->getSchema();
    }

    /**
     * Get the filter name.
     *
     * @return string Filter name
     */
    public function name()
    {
        return 'Group Tagged';
    }

    /**
     * Get a description of the filter.
     *
     * @return string Filter description
     */
    public function description()
    {
        return 'Returns true if a group is tagged';
    }

    /**
     * Get an alias for the filter.
     *
     * @return string Filter alias
     */
    public function alias()
    {
        return 'group_tagged';
    }
}

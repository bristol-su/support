<?php


namespace BristolSU\Support\Filters\Filters\Group;


use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\ControlDB\Contracts\Repositories\Group as GroupRepository;
use BristolSU\ControlDB\Contracts\Repositories\Tags\GroupTag as GroupTagRepositoryContract;
use BristolSU\Support\Filters\Contracts\Filters\GroupFilter;

/**
 * Is the group tagged with a tag?
 */
class GroupTagged extends GroupFilter
{

    /**
     * Group Tag Repository, for retrieving all tags as options
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
     * See if the group is tagged
     * 
     * @param string $settings [ 'tag' => 'full.reference' ]
     * 
     * @return bool
     */
    public function evaluate($settings): bool
    {
        try {
            $tags = $this->groupTagRepository->allThroughGroup($this->group());
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
     * Get all tags as options
     * 
     * @return array
     */
    public function options(): array
    {
        // TODO Transform to a form schema
        $tags = $this->groupTagRepository->all();
        $options = ['tag' => []];
        foreach ($tags as $tag) {
            $options['tag'][$tag->fullReference()] = $tag->name();
        }
        return $options;
    }

    /**
     * Get the filter name
     * 
     * @return string Filter name
     */
    public function name()
    {
        return 'Group Tagged';
    }

    /**
     * Get a description of the filter
     * 
     * @return string Filter description
     */
    public function description()
    {
        return 'Returns true if a group is tagged';
    }

    /**
     * Get an alias for the filter
     * 
     * @return string Filter alias
     */
    public function alias()
    {
        return 'group_tagged';
    }
}

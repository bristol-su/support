<?php


namespace BristolSU\Support\Filters\Filters\Group;


use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Control\Contracts\Repositories\Group as GroupRepository;
use BristolSU\Support\Control\Contracts\Repositories\Tags\GroupTag as GroupTagRepositoryContract;
use BristolSU\Support\Filters\Contracts\Filters\GroupFilter;

/**
 * Class GroupTagged
 * @package BristolSU\Support\Filters\Filters
 */
class GroupTagged extends GroupFilter
{

    /**
     * @var GroupTagRepositoryContract
     */
    private $groupTagRepository;

    /**
     * GroupTagged constructor.
     * @param GroupTagRepositoryContract $groupTagRepository
     * @param GroupRepository $groupRepository
     */
    public function __construct(GroupTagRepositoryContract $groupTagRepository)
    {
        $this->groupTagRepository = $groupTagRepository;
    }

    /**
     * @param string $settings
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
     * @return array
     */
    public function options(): array
    {
        $tags = $this->groupTagRepository->all();
        $options = ['tag' => []];
        foreach ($tags as $tag) {
            $options['tag'][$tag->fullReference()] = $tag->name();
        }
        return $options;
    }

    /**
     * @return mixed|string
     */
    public function name()
    {
        return 'Group Tagged';
    }

    /**
     * @return mixed|string
     */
    public function description()
    {
        return 'Returns true if a group is tagged';
    }

    /**
     * @return mixed|string
     */
    public function alias()
    {
        return 'group_tagged';
    }
}

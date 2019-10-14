<?php


namespace BristolSU\Support\Filters\Filters;


use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Control\Contracts\Repositories\Group as GroupRepository;
use BristolSU\Support\Control\Contracts\Repositories\GroupTag as GroupTagRepositoryContract;
use BristolSU\Support\Filters\Contracts\Filters\GroupFilter;

class GroupTagged extends GroupFilter
{

    /**
     * @var GroupTagRepositoryContract
     */
    private $groupTagRepository;
    /**
     * @var GroupRepository
     */
    private $groupRepository;

    public function __construct(GroupTagRepositoryContract $groupTagRepository, GroupRepository $groupRepository)
    {
        $this->groupTagRepository = $groupTagRepository;
        $this->groupRepository = $groupRepository;
    }

    public function evaluate($settings): bool
    {
        $tags = $this->groupTagRepository->allThroughGroup($this->model());
        foreach($tags as $tag) {
            if($tag->fullReference() === $settings['tag']) {
                return true;
            }
        }
        return false;
    }

    public function options(): array
    {
        $tags = $this->groupTagRepository->all();
        $options = ['tag' => []];
        foreach($tags as $tag) {
            $options['tag'][$tag->fullReference()] = $tag->name();
        }
        return $options;
    }

    public function name()
    {
        return 'Group Tagged';
    }

    public function description()
    {
        return 'Returns true if a group is tagged';
    }

    public function alias()
    {
        return 'group_tagged';
    }

    public function audience($settings)
    {
        $groupTag = $this->groupTagRepository->getTagByFullReference($settings['tag']);
        return $this->groupRepository->allWithTag($groupTag);
    }


}

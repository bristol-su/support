<?php


namespace BristolSU\Support\Control\Repositories\Tags;


use BristolSU\Support\Control\Contracts\Client\Client as ControlClient;
use BristolSU\Support\Control\Contracts\Models\Group as GroupContract;
use BristolSU\Support\Control\Contracts\Models\Tags\GroupTag as GroupTagModelContract;
use BristolSU\Support\Control\Contracts\Models\Tags\GroupTagCategory as GroupTagCategoryContract;
use BristolSU\Support\Control\Contracts\Repositories\Tags\GroupTag as GroupTagContract;
use BristolSU\Support\Control\Models\Tags\GroupTag as GroupTagModel;
use Illuminate\Support\Collection;

/**
 * Class GroupTag
 * @package BristolSU\Support\Control\Repositories
 */
class GroupTag implements GroupTagContract
{

    /**
     * @var ControlClient
     */
    private $client;

    /**
     * GroupTag constructor.
     * @param ControlClient $client
     */
    public function __construct(ControlClient $client)
    {
        $this->client = $client;
    }


    /**
     * Get all group tags
     *
     * @return Collection
     */
    public function all(): Collection
    {
        $groupTagModels = [];
        $groupTags = $this->client->request('get', 'group_tags');
        foreach($groupTags as $groupTag) {
            $groupTagModels[] = new \BristolSU\Support\Control\Models\GroupTag($groupTag);
        }
        return collect($groupTagModels);
    }

    /**
     * Get all group tags which a group is tagged with
     *
     * @param GroupContract $group
     * @return Collection
     */
    public function allThroughGroup(GroupContract $group): Collection
    {
        $groupTagModels = [];
        $groupTags = $this->client->request('get', 'groups/' . $group->id . '/group_tags');
        foreach($groupTags as $groupTag) {
            $groupTagModels[] = new GroupTagModel($groupTag);
        }
        return collect($groupTagModels);
    }

    /**
     * Get a tag by the full reference
     *
     * @param string $reference
     * @return mixed
     */
    public function getTagByFullReference(string $reference): GroupTagModelContract
    {
        foreach($this->all() as $tag) {
            if($tag->fullReference() === $reference) {
                return $tag;
            }
        }
    }

    /**
     * Get a group tag by id
     *
     * @param int $id
     * @return GroupTagModelContract
     */
    public function getById(int $id): GroupTagModelContract
    {
        $response = $this->client->request('get', 'group_tags/' . $id);
        return new \BristolSU\Support\Control\Models\Tags\GroupTag($response);
    }

    /**
     * Get all group tags belonging to a group tag category
     *
     * @param GroupTagCategoryContract $groupTagCategory
     * @return Collection
     */
    public function allThroughGroupTagCategory(GroupTagCategoryContract $groupTagCategory): Collection
    {
        $groupTagModels = [];
        $groupTags = $this->client->request('get', 'group_tag_category/' . $groupTagCategory->id() . '/group_tags');
        foreach($groupTags as $groupTag) {
            $groupTagModels[] = new GroupTagModel($groupTag);
        }
        return collect($groupTagModels);
    }
}
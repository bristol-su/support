<?php


namespace BristolSU\Support\Control\Repositories\Tags;


use BristolSU\Support\Control\Contracts\Client\Client as ControlClient;
use BristolSU\Support\Control\Contracts\Models\Tags\GroupTag as GroupTagModel;
use BristolSU\Support\Control\Contracts\Models\Tags\GroupTagCategory as GroupTagCategoryModelContract;
use BristolSU\Support\Control\Contracts\Repositories\Tags\GroupTagCategory as GroupTagCategoryContract;
use BristolSU\Support\Control\Models\Tags\GroupTagCategory as GroupTagCategoryModel;
use Illuminate\Support\Collection;

/**
 * Class GroupTag
 * @package BristolSU\Support\Control\Repositories
 */
class GroupTagCategory implements GroupTagCategoryContract
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
     * Get all group tag categories
     *
     * @return Collection
     */
    public function all(): Collection
    {
        $groupTagCategories = [];
        $response = $this->client->request('get', 'group_tag_category');
        foreach($response as $groupTagCategory) {
            $groupTagCategories[] = new GroupTagCategoryModel($groupTagCategory);
        }
        return collect($groupTagCategories);
    }

    /**
     * Get the group tag category of a group tag
     *
     * @param GroupTagModel $group
     * @return GroupTagCategoryModelContract
     */
    public function getThroughTag(GroupTagModel $groupTag): GroupTagCategoryModelContract
    {
        $response = $this->client->request('get', 'group_tags/' . $groupTag->id() . '/group_tag_category');
        return new \BristolSU\Support\Control\Models\Tags\GroupTagCategory($response);    
    }

    /**
     * Get a tag category by the reference
     *
     * @param $reference
     * @return mixed
     */
    public function getByReference(string $reference): GroupTagCategoryModelContract
    {
        // TODO Implement method
    }

    /**
     * Get a group tag category by id
     *
     * @param int $id
     * @return GroupTagCategoryModelContract
     */
    public function getById(int $id): GroupTagCategoryModelContract
    {
        $response = $this->client->request('get', 'group_tag_category/' . $id);
        return new \BristolSU\Support\Control\Models\Tags\GroupTagCategory($response);
    }
}
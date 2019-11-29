<?php


namespace BristolSU\Support\Control\Repositories;


use BristolSU\Support\Control\Contracts\Client\Client as ControlClient;
use BristolSU\Support\Control\Contracts\Models\Group as GroupModel;
use BristolSU\Support\Control\Contracts\Models\Tags\GroupTag as GroupTagModel;
use BristolSU\Support\Control\Contracts\Models\User as UserModel;
use BristolSU\Support\Control\Contracts\Repositories\Group as GroupContract;
use Illuminate\Support\Collection;

/**
 * Class Group
 * @package BristolSU\Support\Control\Repositories
 */
class Group implements GroupContract
{

    /**
     * @var ControlClient
     */
    private $client;

    /**
     * Group constructor.
     * @param ControlClient $client
     */
    public function __construct(ControlClient $client)
    {
        $this->client = $client;
    }

    /**
     * Get a group by ID
     *
     * @param $id
     * @return GroupModel
     */
    public function getById(int $id): GroupModel
    {
        $response = $this->client->request(
            'get',
            'groups/'.$id
        );

        return new \BristolSU\Support\Control\Models\Group($response);
    }

    /**
     * Get all groups with a specific tag
     *
     * @param GroupTagModel $groupTag
     * @return Collection
     */
    public function allThroughTag(GroupTagModel $groupTag): Collection
    {
        $response = $this->client->request(
            'get',
            'group_tags/'.$groupTag->id().'/groups'
        );

        $groups = [];
        foreach ($response as $group) {
            $groups[] = new \BristolSU\Support\Control\Models\Group($group);
        }
        return collect($groups);
    }
    
    /**
     * Get all groups
     *
     * @return Collection
     */
    public function all(): Collection
    {
        $response = $this->client->request(
            'get',
            'groups'
        );

        $groups = [];
        foreach ($response as $group) {
            $groups[] = new \BristolSU\Support\Control\Models\Group($group);
        }
        return collect($groups);
    }

    /**
     * Get all groups the given user is a member of
     *
     * @param $id
     * @return Collection
     */
    public function allThroughUser(UserModel $user): Collection
    {
        $groups = $this->client->request('get', 'students/'.$user->id().'/groups');
        $modelGroups = new Collection;
        foreach ($groups as $group) {
            $modelGroups->push(new \BristolSU\Support\Control\Models\Group($group));
        }
        return $modelGroups;
    }
}

<?php


namespace BristolSU\Support\Control\Repositories;


use BristolSU\Support\Control\Contracts\Client\Client as ControlClient;
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
     * @param $id
     * @return \BristolSU\Support\Control\Models\Group|mixed
     */
    public function getById($id)
    {
        $response = $this->client->request(
            'get',
            'groups/' . $id
        );

        return new \BristolSU\Support\Control\Models\Group($response);
    }

    /**
     * @param \BristolSU\Support\Control\Contracts\Models\GroupTag $tag
     * @return array|mixed
     */
    public function allWithTag(\BristolSU\Support\Control\Contracts\Models\GroupTag $tag)
    {
        $response = $this->client->request(
            'get',
            'group_tags/' . $tag->id() . '/groups'
        );

        $groups = [];
        foreach($response as $group) {
            $groups[] = new \BristolSU\Support\Control\Models\Group($group);
        }
        return $groups;
    }

    /**
     * @return array
     */
    public function all()
    {
        $response = $this->client->request(
            'get',
            'groups'
        );

        $groups = [];
        foreach($response as $group) {
            $groups[] = new \BristolSU\Support\Control\Models\Group($group);
        }
        return $groups;
    }

    /**
     * @param $id
     * @return Collection
     */
    public function allFromStudentControlID($id): Collection
    {
        $groups = $this->client->request('get', 'students/' . $id . '/groups');
        $modelGroups = new Collection;
        foreach($groups as $group) {
            $modelGroups->push(new \BristolSU\Support\Control\Models\Group($group));
        }
        return $modelGroups;
    }
}

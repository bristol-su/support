<?php


namespace BristolSU\Support\Control\Repositories;


use BristolSU\Support\Control\Contracts\Client\Client as ControlClient;
use BristolSU\Support\Control\Contracts\Repositories\Group as GroupContract;

class Group implements GroupContract
{

    /**
     * @var ControlClient
     */
    private $client;

    public function __construct(ControlClient $client)
    {
        $this->client = $client;
    }

    public function getById($id)
    {
        $response = $this->client->request(
            'get',
            'groups/' . $id
        );

        return new \BristolSU\Support\Control\Models\Group($response);
    }

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
}

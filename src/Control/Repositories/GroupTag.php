<?php


namespace BristolSU\Support\Control\Repositories;


use BristolSU\Support\Control\Contracts\Client\Client as ControlClient;
use BristolSU\Support\Control\Contracts\Models\Group as GroupContract;
use BristolSU\Support\Control\Models\GroupTag as GroupTagModel;
use BristolSU\Support\Control\Contracts\Repositories\GroupTag as GroupTagContract;
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
     * @return array
     */
    public function all()
    {
        $groupTagModels = [];
        $groupTags = $this->client->request('get', 'group_tags');
        foreach($groupTags as $groupTag) {
            $groupTagModels[] = new GroupTagModel($groupTag);
        }
        return $groupTagModels;
    }

    /**
     * @param GroupContract $group
     * @return array|mixed
     */
    public function allThroughGroup(GroupContract $group)
    {
        $groupTagModels = [];
        $groupTags = $this->client->request('get', 'groups/' . $group->id . '/group_tags');
        foreach($groupTags as $groupTag) {
            $groupTagModels[] = new GroupTagModel($groupTag);
        }
        return $groupTagModels;
    }

    /**
     * @param $reference
     * @return mixed
     */
    public function getTagByFullReference($reference)
    {

        foreach($this->all() as $tag) {
            if($tag->fullReference() === $reference) {
                return $tag;
            }
        }
    }
}

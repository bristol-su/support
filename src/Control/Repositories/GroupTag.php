<?php


namespace BristolSU\Support\Control\Repositories;


use BristolSU\Support\Control\Contracts\Client\Client as ControlClient;
use BristolSU\Support\Control\Contracts\Models\Group as GroupContract;
use BristolSU\Support\Control\Models\GroupTag as GroupTagModel;
use BristolSU\Support\Control\Contracts\Repositories\GroupTag as GroupTagContract;
use Illuminate\Support\Collection;

class GroupTag implements GroupTagContract
{

    /**
     * @var ControlClient
     */
    private $client;

    public function __construct(ControlClient $client)
    {
        $this->client = $client;
    }

    public function all()
    {
        $groupTagModels = [];
        $groupTags = $this->client->request('get', 'group_tags');
        foreach($groupTags as $groupTag) {
            $groupTagModels[] = new GroupTagModel($groupTag);
        }
        return $groupTagModels;
    }

    public function allThroughGroup(GroupContract $group)
    {
        $groupTagModels = [];
        $groupTags = $this->client->request('get', 'groups/' . $group->id . '/group_tags');
        foreach($groupTags as $groupTag) {
            $groupTagModels[] = new GroupTagModel($groupTag);
        }
        return $groupTagModels;
    }

    public function getTagByFullReference($reference)
    {

        foreach($this->all() as $tag) {
            if($tag->fullReference() === $reference) {
                return $tag;
            }
        }
    }
}

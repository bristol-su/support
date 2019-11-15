<?php


namespace BristolSU\Support\Control\Repositories\Tags;


use BristolSU\Support\Control\Contracts\Client\Client as ControlClient;
use BristolSU\Support\Control\Contracts\Models\User as UserContract;
use BristolSU\Support\Control\Contracts\Models\Tags\UserTag as UserTagModelContract;
use BristolSU\Support\Control\Contracts\Models\Tags\UserTagCategory as UserTagCategoryContract;
use BristolSU\Support\Control\Contracts\Repositories\Tags\UserTag as UserTagContract;
use BristolSU\Support\Control\Models\Tags\UserTag as UserTagModel;
use Illuminate\Support\Collection;

/**
 * Class UserTag
 * @package BristolSU\Support\Control\Repositories
 */
class UserTag implements UserTagContract
{

    /**
     * @var ControlClient
     */
    private $client;

    /**
     * UserTag constructor.
     * @param ControlClient $client
     */
    public function __construct(ControlClient $client)
    {
        $this->client = $client;
    }


    /**
     * Get all user tags
     *
     * @return Collection
     */
    public function all(): Collection
    {
        $userTagModels = [];
        $userTags = $this->client->request('get', 'user_tags');
        foreach($userTags as $userTag) {
            $userTagModels[] = new \BristolSU\Support\Control\Models\UserTag($userTag);
        }
        return collect($userTagModels);
    }

    /**
     * Get all user tags which a user is tagged with
     *
     * @param UserContract $user
     * @return Collection
     */
    public function allThroughUser(UserContract $user): Collection
    {
        $userTagModels = [];
        $userTags = $this->client->request('get', 'users/' . $user->id . '/user_tags');
        foreach($userTags as $userTag) {
            $userTagModels[] = new UserTagModel($userTag);
        }
        return collect($userTagModels);
    }

    /**
     * Get a tag by the full reference
     *
     * @param string $reference
     * @return mixed
     */
    public function getTagByFullReference(string $reference): UserTagModelContract
    {
        foreach($this->all() as $tag) {
            if($tag->fullReference() === $reference) {
                return $tag;
            }
        }
    }

    /**
     * Get a user tag by id
     *
     * @param int $id
     * @return UserTagModelContract
     */
    public function getById(int $id): UserTagModelContract
    {
        $response = $this->client->request('get', 'user_tags/' . $id);
        return new \BristolSU\Support\Control\Models\Tags\UserTag($response);
    }

    /**
     * Get all user tags belonging to a user tag category
     *
     * @param UserTagCategoryContract $userTagCategory
     * @return Collection
     */
    public function allThroughUserTagCategory(UserTagCategoryContract $userTagCategory): Collection
    {
        $userTagModels = [];
        $userTags = $this->client->request('get', 'user_tag_category/' . $userTagCategory->id() . '/user_tags');
        foreach($userTags as $userTag) {
            $userTagModels[] = new UserTagModel($userTag);
        }
        return collect($userTagModels);
    }
}
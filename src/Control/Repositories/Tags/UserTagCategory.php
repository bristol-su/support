<?php


namespace BristolSU\Support\Control\Repositories\Tags;


use BristolSU\Support\Control\Contracts\Client\Client as ControlClient;
use BristolSU\Support\Control\Contracts\Models\Tags\UserTag as UserTagModel;
use BristolSU\Support\Control\Contracts\Models\Tags\UserTagCategory as UserTagCategoryModelContract;
use BristolSU\Support\Control\Contracts\Repositories\Tags\UserTagCategory as UserTagCategoryContract;
use BristolSU\Support\Control\Models\Tags\UserTagCategory as UserTagCategoryModel;
use Illuminate\Support\Collection;

/**
 * Class UserTag
 * @package BristolSU\Support\Control\Repositories
 */
class UserTagCategory implements UserTagCategoryContract
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
     * Get all user tag categories
     *
     * @return Collection
     */
    public function all(): Collection
    {
        $userTagCategories = [];
        $response = $this->client->request('get', 'user_tag_category');
        foreach ($response as $userTagCategory) {
            $userTagCategories[] = new UserTagCategoryModel($userTagCategory);
        }
        return collect($userTagCategories);
    }

    /**
     * Get the user tag category of a user tag
     *
     * @param UserTagModel $user
     * @return UserTagCategoryModelContract
     */
    public function allThroughTag(UserTagModel $userTag): UserTagCategoryModelContract
    {
        $response = $this->client->request('get', 'user_tags/'.$userTag->id().'/user_tag_category');
        return new \BristolSU\Support\Control\Models\Tags\UserTagCategory($response);    
    }

    /**
     * Get a tag category by the reference
     *
     * @param $reference
     * @return mixed
     */
    public function getByReference(string $reference): UserTagCategoryModelContract
    {
        // TODO Implement method
    }

    /**
     * Get a user tag category by id
     *
     * @param int $id
     * @return UserTagCategoryModelContract
     */
    public function getById(int $id): UserTagCategoryModelContract
    {
        $response = $this->client->request('get', 'user_tag_category/'.$id);
        return new \BristolSU\Support\Control\Models\Tags\UserTagCategory($response);
    }
}
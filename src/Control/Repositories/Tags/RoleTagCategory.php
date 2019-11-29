<?php


namespace BristolSU\Support\Control\Repositories\Tags;


use BristolSU\Support\Control\Contracts\Client\Client as ControlClient;
use BristolSU\Support\Control\Contracts\Models\Tags\RoleTag as RoleTagModel;
use BristolSU\Support\Control\Contracts\Models\Tags\RoleTagCategory as RoleTagCategoryModelContract;
use BristolSU\Support\Control\Contracts\Repositories\Tags\RoleTagCategory as RoleTagCategoryContract;
use BristolSU\Support\Control\Models\Tags\RoleTagCategory as RoleTagCategoryModel;
use Illuminate\Support\Collection;

/**
 * Class RoleTag
 * @package BristolSU\Support\Control\Repositories
 */
class RoleTagCategory implements RoleTagCategoryContract
{

    /**
     * @var ControlClient
     */
    private $client;

    /**
     * RoleTag constructor.
     * @param ControlClient $client
     */
    public function __construct(ControlClient $client)
    {
        $this->client = $client;
    }


    /**
     * Get all role tag categories
     *
     * @return Collection
     */
    public function all(): Collection
    {
        $roleTagCategories = [];
        $response = $this->client->request('get', 'role_tag_category');
        foreach ($response as $roleTagCategory) {
            $roleTagCategories[] = new RoleTagCategoryModel($roleTagCategory);
        }
        return collect($roleTagCategories);
    }

    /**
     * Get the role tag category of a role tag
     *
     * @param RoleTagModel $role
     * @return RoleTagCategoryModelContract
     */
    public function allThroughTag(RoleTagModel $roleTag): RoleTagCategoryModelContract
    {
        $response = $this->client->request('get', 'role_tags/'.$roleTag->id().'/role_tag_category');
        return new \BristolSU\Support\Control\Models\Tags\RoleTagCategory($response);    
    }

    /**
     * Get a tag category by the reference
     *
     * @param $reference
     * @return mixed
     */
    public function getByReference(string $reference): RoleTagCategoryModelContract
    {
        // TODO Implement method
    }

    /**
     * Get a role tag category by id
     *
     * @param int $id
     * @return RoleTagCategoryModelContract
     */
    public function getById(int $id): RoleTagCategoryModelContract
    {
        $response = $this->client->request('get', 'role_tag_category/'.$id);
        return new \BristolSU\Support\Control\Models\Tags\RoleTagCategory($response);
    }
}
<?php


namespace BristolSU\Support\Control\Repositories\Tags;


use BristolSU\Support\Control\Contracts\Client\Client as ControlClient;
use BristolSU\Support\Control\Contracts\Models\Role as RoleContract;
use BristolSU\Support\Control\Contracts\Models\Tags\RoleTag as RoleTagModelContract;
use BristolSU\Support\Control\Contracts\Models\Tags\RoleTagCategory as RoleTagCategoryContract;
use BristolSU\Support\Control\Contracts\Repositories\Tags\RoleTag as RoleTagContract;
use BristolSU\Support\Control\Models\Tags\RoleTag as RoleTagModel;
use Illuminate\Support\Collection;

/**
 * Class RoleTag
 * @package BristolSU\Support\Control\Repositories
 */
class RoleTag implements RoleTagContract
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
     * Get all role tags
     *
     * @return Collection
     */
    public function all(): Collection
    {
        $roleTagModels = [];
        $roleTags = $this->client->request('get', 'role_tags');
        foreach($roleTags as $roleTag) {
            $roleTagModels[] = new \BristolSU\Support\Control\Models\RoleTag($roleTag);
        }
        return collect($roleTagModels);
    }

    /**
     * Get all role tags which a role is tagged with
     *
     * @param RoleContract $role
     * @return Collection
     */
    public function allThroughRole(RoleContract $role): Collection
    {
        $roleTagModels = [];
        $roleTags = $this->client->request('get', 'roles/' . $role->id . '/role_tags');
        foreach($roleTags as $roleTag) {
            $roleTagModels[] = new RoleTagModel($roleTag);
        }
        return collect($roleTagModels);
    }

    /**
     * Get a tag by the full reference
     *
     * @param string $reference
     * @return mixed
     */
    public function getTagByFullReference(string $reference): RoleTagModelContract
    {
        foreach($this->all() as $tag) {
            if($tag->fullReference() === $reference) {
                return $tag;
            }
        }
    }

    /**
     * Get a role tag by id
     *
     * @param int $id
     * @return RoleTagModelContract
     */
    public function getById(int $id): RoleTagModelContract
    {
        $response = $this->client->request('get', 'role_tags/' . $id);
        return new \BristolSU\Support\Control\Models\Tags\RoleTag($response);
    }

    /**
     * Get all role tags belonging to a role tag category
     *
     * @param RoleTagCategoryContract $roleTagCategory
     * @return Collection
     */
    public function allThroughRoleTagCategory(RoleTagCategoryContract $roleTagCategory): Collection
    {
        $roleTagModels = [];
        $roleTags = $this->client->request('get', 'role_tag_category/' . $roleTagCategory->id() . '/role_tags');
        foreach($roleTags as $roleTag) {
            $roleTagModels[] = new RoleTagModel($roleTag);
        }
        return collect($roleTagModels);
    }
}
<?php


namespace BristolSU\Support\Control\Repositories\Tags;


use BristolSU\Support\Control\Contracts\Client\Client as ControlClient;
use BristolSU\Support\Control\Contracts\Models\Position as PositionContract;
use BristolSU\Support\Control\Contracts\Models\Tags\PositionTag as PositionTagModelContract;
use BristolSU\Support\Control\Contracts\Models\Tags\PositionTagCategory as PositionTagCategoryContract;
use BristolSU\Support\Control\Contracts\Repositories\Tags\PositionTag as PositionTagContract;
use BristolSU\Support\Control\Models\Tags\PositionTag as PositionTagModel;
use Illuminate\Support\Collection;

/**
 * Class PositionTag
 * @package BristolSU\Support\Control\Repositories
 */
class PositionTag implements PositionTagContract
{

    /**
     * @var ControlClient
     */
    private $client;

    /**
     * PositionTag constructor.
     * @param ControlClient $client
     */
    public function __construct(ControlClient $client)
    {
        $this->client = $client;
    }


    /**
     * Get all position tags
     *
     * @return Collection
     */
    public function all(): Collection
    {
        $positionTagModels = [];
        $positionTags = $this->client->request('get', 'position_tags');
        foreach ($positionTags as $positionTag) {
            $positionTagModels[] = new \BristolSU\Support\Control\Models\PositionTag($positionTag);
        }
        return collect($positionTagModels);
    }

    /**
     * Get all position tags which a position is tagged with
     *
     * @param PositionContract $position
     * @return Collection
     */
    public function allThroughPosition(PositionContract $position): Collection
    {
        $positionTagModels = [];
        $positionTags = $this->client->request('get', 'positions/'.$position->id.'/position_tags');
        foreach ($positionTags as $positionTag) {
            $positionTagModels[] = new PositionTagModel($positionTag);
        }
        return collect($positionTagModels);
    }

    /**
     * Get a tag by the full reference
     *
     * @param string $reference
     * @return mixed
     */
    public function getTagByFullReference(string $reference): PositionTagModelContract
    {
        foreach ($this->all() as $tag) {
            if ($tag->fullReference() === $reference) {
                return $tag;
            }
        }
    }

    /**
     * Get a position tag by id
     *
     * @param int $id
     * @return PositionTagModelContract
     */
    public function getById(int $id): PositionTagModelContract
    {
        $response = $this->client->request('get', 'position_tags/'.$id);
        return new \BristolSU\Support\Control\Models\Tags\PositionTag($response);
    }

    /**
     * Get all position tags belonging to a position tag category
     *
     * @param PositionTagCategoryContract $positionTagCategory
     * @return Collection
     */
    public function allThroughPositionTagCategory(PositionTagCategoryContract $positionTagCategory): Collection
    {
        $positionTagModels = [];
        $positionTags = $this->client->request('get', 'position_tag_category/'.$positionTagCategory->id().'/position_tags');
        foreach ($positionTags as $positionTag) {
            $positionTagModels[] = new PositionTagModel($positionTag);
        }
        return collect($positionTagModels);
    }
}
<?php


namespace BristolSU\Support\Control\Repositories\Tags;


use BristolSU\Support\Control\Contracts\Client\Client as ControlClient;
use BristolSU\Support\Control\Contracts\Models\Tags\PositionTag as PositionTagModel;
use BristolSU\Support\Control\Contracts\Models\Tags\PositionTagCategory as PositionTagCategoryModelContract;
use BristolSU\Support\Control\Contracts\Repositories\Tags\PositionTagCategory as PositionTagCategoryContract;
use BristolSU\Support\Control\Models\Tags\PositionTagCategory as PositionTagCategoryModel;
use Illuminate\Support\Collection;

/**
 * Class PositionTag
 * @package BristolSU\Support\Control\Repositories
 */
class PositionTagCategory implements PositionTagCategoryContract
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
     * Get all position tag categories
     *
     * @return Collection
     */
    public function all(): Collection
    {
        $positionTagCategories = [];
        $response = $this->client->request('get', 'position_tag_category');
        foreach($response as $positionTagCategory) {
            $positionTagCategories[] = new PositionTagCategoryModel($positionTagCategory);
        }
        return collect($positionTagCategories);
    }

    /**
     * Get the position tag category of a position tag
     *
     * @param PositionTagModel $position
     * @return PositionTagCategoryModelContract
     */
    public function allThroughTag(PositionTagModel $positionTag): PositionTagCategoryModelContract
    {
        $response = $this->client->request('get', 'position_tags/' . $positionTag->id() . '/position_tag_category');
        return new \BristolSU\Support\Control\Models\Tags\PositionTagCategory($response);    
    }

    /**
     * Get a tag category by the reference
     *
     * @param $reference
     * @return mixed
     */
    public function getByReference(string $reference): PositionTagCategoryModelContract
    {
        // TODO Implement method
    }

    /**
     * Get a position tag category by id
     *
     * @param int $id
     * @return PositionTagCategoryModelContract
     */
    public function getById(int $id): PositionTagCategoryModelContract
    {
        $response = $this->client->request('get', 'position_tag_category/' . $id);
        return new \BristolSU\Support\Control\Models\Tags\PositionTagCategory($response);
    }
}
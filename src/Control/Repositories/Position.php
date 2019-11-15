<?php


namespace BristolSU\Support\Control\Repositories;


use BristolSU\Support\Control\Contracts\Client\Client as ControlClient;
use BristolSU\Support\Control\Contracts\Models\Position as PositionModel;
use BristolSU\Support\Control\Contracts\Repositories\Position as PositionContract;
use Illuminate\Support\Collection;

/**
 * Class Position
 * @package BristolSU\Support\Control\Repositories
 */
class Position implements PositionContract
{

    /**
     * @var ControlClient
     */
    private $client;

    /**
     * Position constructor.
     * @param ControlClient $client
     */
    public function __construct(ControlClient $client)
    {
        $this->client = $client;
    }


    /**
     * Get all positions
     *
     * @return Collection
     */
    public function all(): Collection
    {
        $positions = $this->client->request('get', 'positions');
        $modelPositions = new Collection;
        foreach($positions as $position) {
            $modelPositions->push(new \BristolSU\Support\Control\Models\Position($position));
        }
        return $modelPositions;
    }

    /**
     * Get a position by a given ID
     *
     * @param int $id
     * @return PositionModel
     */
    public function getById(int $id): PositionModel
    {
        $response = $this->client->request('get', 'positions/' . $id);
        return new \BristolSU\Support\Control\Models\Position($response);
    }
}

<?php


namespace BristolSU\Support\Control\Repositories;


use BristolSU\Support\Control\Contracts\Client\Client as ControlClient;
use BristolSU\Support\Control\Contracts\Repositories\Position as PositionContract;
use Illuminate\Support\Collection;

class Position implements PositionContract
{

    /**
     * @var ControlClient
     */
    private $client;

    public function __construct(ControlClient $client)
    {
        $this->client = $client;
    }

    public function all(): Collection
    {
        $positions = $this->client->request('get', 'positions');
        $modelPositions = new Collection;
        foreach($positions as $position) {
            $modelPositions->push(new \BristolSU\Support\Control\Models\Position($position));
        }
        return $modelPositions;
    }

}

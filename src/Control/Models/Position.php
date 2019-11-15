<?php


namespace BristolSU\Support\Control\Models;


use BristolSU\Support\Control\Contracts\Models\Position as PositionContract;
use BristolSU\Support\Control\Contracts\Repositories\Tags\PositionTag;
use Illuminate\Support\Collection;

/**
 * Class Position
 * @package BristolSU\Support\Control\Models
 */
class Position extends Model implements PositionContract
{

    /**
     * Name of the position
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Description of the position
     *
     * @return string
     */
    public function description(): string
    {
        return $this->description;
    }

    /**
     * ID of the position
     *
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }

    /**
     * Roles with this position
     *
     * @return Collection
     */
    public function roles(): Collection
    {
        return app(\BristolSU\Support\Control\Contracts\Repositories\Role::class)->allThroughPosition($this);
    }

    /**
     * Tags the position is tagged with
     *
     * @return Collection
     */
    public function tags(): Collection
    {
        return app(PositionTag::class)->allThroughPosition($this);
    }
}

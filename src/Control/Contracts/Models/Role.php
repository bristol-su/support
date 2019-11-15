<?php


namespace BristolSU\Support\Control\Contracts\Models;


use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;

/**
 * Interface Role
 * @package BristolSU\Support\Control\Contracts\Models
 */
interface Role extends Authenticatable
{

    /**
     * ID of the position
     * 
     * @return mixed
     */
    public function positionId();

    /**
     * ID of the group
     * 
     * @return mixed
     */
    public function groupId();

    /**
     * Custom name of the position.
     * 
     * This does not need to be the same as the actual position name. It may instead be anything you like, to allow for
     * more granular control over the positions and roles owned by an individual, whilst not creating too many positions.
     * 
     * @return string
     */
    public function positionName(): string;

    /**
     * Position belonging to the role
     * 
     * @return Position
     */
    public function position(): Position;

    /**
     * Group belonging to the role
     * 
     * @return Group
     */
    public function group(): Group;

    /**
     * Users who occupy the role
     * 
     * @return Collection
     */
    public function users(): Collection;

    /**
     * Tags the role is tagged with
     *
     * @return Collection
     */
    public function tags(): Collection;

    /**
     * Get the ID of the role
     * 
     * @return int
     */
    public function id(): int;
    
}

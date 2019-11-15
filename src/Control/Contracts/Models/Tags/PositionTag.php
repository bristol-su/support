<?php


namespace BristolSU\Support\Control\Contracts\Models\Tags;


use Illuminate\Support\Collection;

/**
 * Interface PositionTag
 * @package BristolSU\Support\Control\Contracts\Models
 */
interface PositionTag
{

    /**
     * ID of the position tag
     *
     * @return int
     */
    public function id(): int;

    /**
     * Name of the tag
     *
     * @return string
     */
    public function name(): string;

    /**
     * Description of the tag
     *
     * @return string
     */
    public function description(): string;

    /**
     * Reference of the tag
     *
     * @return string
     */
    public function reference(): string;

    /**
     * ID of the tag category
     * @return int
     */
    public function categoryId(): int;

    /**
     * Tag Category
     *
     * @return PositionTagCategory
     */
    public function category(): PositionTagCategory;

    /**
     * Full reference of the tag
     *
     * This should be the tag category reference and the tag reference, separated with a period.
     * @return string
     */
    public function fullReference(): string;

    /**
     * Positions that have this tag
     *
     * @return Collection
     */
    public function positions(): Collection;
}

<?php

namespace BristolSU\Support\Control\Contracts\Models\Tags;

use Illuminate\Support\Collection;

/**
 * Interface PositionTag
 * @package BristolSU\Support\Control\Contracts\Models
 */
interface PositionTagCategory
{

    /**
     * ID of the tag category
     *
     * @return mixed
     */
    public function id();

    /**
     * Name of the tag category
     *
     * @return string
     */
    public function name(): string;

    /**
     * Deacription of the tag category
     *
     * @return string
     */
    public function description(): string;

    /**
     * Reference of the tag category
     *
     * @return string
     */
    public function reference(): string;

    /**
     * All tags under this tag category
     *
     * @return Collection
     */
    public function tags(): Collection;
}

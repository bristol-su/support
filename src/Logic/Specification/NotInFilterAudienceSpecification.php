<?php

namespace BristolSU\Support\Logic\Specification;

use BristolSU\Support\Filters\Contracts\FilterInstance;
use BristolSU\Support\Filters\Contracts\FilterRepository;
use BristolSU\Support\Logic\Contracts\Specification;

/**
 * Class NotInFilterAudienceSpecification
 * @package BristolSU\Support\Logic\Specification
 */
class NotInFilterAudienceSpecification implements Specification
{

    /**
     * @var
     */
    private $item;
    /**
     * @var FilterInstance
     */
    private $filter;
    /**
     * @var FilterRepository
     */
    private $filterRepository;

    /**
     * NotInFilterAudienceSpecification constructor.
     * @param $item
     * @param FilterInstance $filter
     * @param FilterRepository $filterRepository
     */
    public function __construct($item, FilterInstance $filter, FilterRepository $filterRepository)
    {
        $this->item = $item;
        $this->filter = $filter;
        $this->filterRepository = $filterRepository;
    }

    /**
     * @return bool
     */
    public function isSatisfied(): bool
    {
        $filter = $this->filterRepository->getByAlias($this->filter->alias());
        return !in_array(
            $this->item,
            $filter->audience($this->filter->settings())
        );
    }
}

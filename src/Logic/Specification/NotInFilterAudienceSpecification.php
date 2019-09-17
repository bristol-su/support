<?php

namespace BristolSU\Support\Logic\Specification;

use BristolSU\Support\Filters\Contracts\FilterInstance;
use BristolSU\Support\Filters\Contracts\FilterRepository;
use BristolSU\Support\Logic\Contracts\Specification;

class NotInFilterAudienceSpecification implements Specification
{

    private $item;
    /**
     * @var FilterInstance
     */
    private $filter;
    /**
     * @var FilterRepository
     */
    private $filterRepository;

    public function __construct($item, FilterInstance $filter, FilterRepository $filterRepository)
    {
        $this->item = $item;
        $this->filter = $filter;
        $this->filterRepository = $filterRepository;
    }

    public function isSatisfied(): bool
    {
        $filter = $this->filterRepository->getByAlias($this->filter->alias());
        return !in_array(
            $this->item,
            $filter->audience($this->filter->settings())
        );
    }
}

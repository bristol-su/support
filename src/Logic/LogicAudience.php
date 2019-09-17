<?php

namespace BristolSU\Support\Logic;

use BristolSU\Support\Filters\Contracts\FilterRepository;
use BristolSU\Support\Logic\Contracts\AudienceFactory as AudienceFactoryContract;
use BristolSU\Support\Logic\Contracts\LogicAudience as LogicAudienceContract;
use BristolSU\Support\Logic\Specification\AndSpecification;
use BristolSU\Support\Logic\Specification\InFilterAudienceSpecification;
use BristolSU\Support\Logic\Specification\NotInFilterAudienceSpecification;
use BristolSU\Support\Logic\Specification\OrSpecification;

class LogicAudience implements LogicAudienceContract
{

    /**
     * @var FilterRepository
     */
    private $filterRepository;
    /**
     * @var AudienceFactoryContract
     */
    private $audienceFactory;

    /**
     * LogicAudience constructor.
     * @param FilterRepository $filterRepository
     * @param AudienceFactoryContract $audienceFactory
     */
    public function __construct(FilterRepository $filterRepository, AudienceFactoryContract $audienceFactory)
    {
        $this->filterRepository = $filterRepository;
        $this->audienceFactory = $audienceFactory;
    }

    public function audience(Logic $logic)
    {

        return $this->audienceFactory->for($logic->for)->filter(function($item) use ($logic) {
            return $this->isInAudience($item, $logic);
        });
    }

    public function isInAudience($item, $logic)
    {
        $allTrue = [];
        $anyTrue = [];
        $allFalse = [];
        $anyFalse = [];

        foreach($logic->allTrueFilters as $filter) {
            $allTrue[] = new InFilterAudienceSpecification($item, $filter, $this->filterRepository);
        }

        foreach($logic->anyTrueFilters as $filter) {
            $anyTrue[] = new InFilterAudienceSpecification($item, $filter, $this->filterRepository);
        }

        foreach($logic->allFalseFilters as $filter) {
            $allFalse[] = new NotInFilterAudienceSpecification($item, $filter, $this->filterRepository);
        }

        foreach($logic->anyFalseFilters as $filter) {
            $anyFalse[] = new NotInFilterAudienceSpecification($item, $filter, $this->filterRepository);
        }

        return (new AndSpecification(
            new AndSpecification(...$allTrue),
            new OrSpecification(...$anyTrue),
            new AndSpecification(...$allFalse),
            new OrSpecification(...$anyFalse)
        ))->isSatisfied();
    }

}

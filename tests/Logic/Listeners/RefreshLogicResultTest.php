<?php

namespace BristolSU\Support\Tests\Logic\Listeners;

use BristolSU\Support\Filters\Events\AudienceChanged;
use BristolSU\Support\Filters\FilterInstance;
use BristolSU\Support\Logic\Jobs\CacheLogic;
use BristolSU\Support\Logic\Jobs\CacheLogicForGroup;
use BristolSU\Support\Logic\Jobs\CacheLogicForRole;
use BristolSU\Support\Logic\Jobs\CacheLogicForSingleCombination;
use BristolSU\Support\Logic\Jobs\CacheLogicForUser;
use BristolSU\Support\Logic\Listeners\RefreshLogicResult;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Support\Facades\Bus;

class RefreshLogicResultTest extends TestCase
{

    /** @test */
    public function it_dispatches_a_CacheLogic_if_the_model_is_null(){
        Bus::fake([CacheLogic::class, CacheLogicForUser::class, CacheLogicForGroup::class, CacheLogicForRole::class, CacheLogicForSingleCombination::class]);

        $filterInstances = FilterInstance::factory()->count(2)->createQuietly();

        $listener = new RefreshLogicResult();
        $listener->handle(new AudienceChanged($filterInstances->all(), null));

        Bus::assertDispatchedTimes(CacheLogic::class, 2);
        Bus::assertNotDispatched(CacheLogicForUser::class);
        Bus::assertNotDispatched(CacheLogicForGroup::class);
        Bus::assertNotDispatched(CacheLogicForRole::class);
        Bus::assertNotDispatched(CacheLogicForSingleCombination::class);
    }

    /** @test */
    public function it_dispatches_a_CacheLogicForUser_if_the_model_is_a_user(){
        Bus::fake([CacheLogic::class, CacheLogicForUser::class, CacheLogicForGroup::class, CacheLogicForRole::class, CacheLogicForSingleCombination::class]);

        $filterInstances = FilterInstance::factory()->count(2)->createQuietly();

        $listener = new RefreshLogicResult();
        $listener->handle(new AudienceChanged($filterInstances->all(), $this->newUser()));

        Bus::assertNotDispatched(CacheLogic::class);
        Bus::assertDispatchedTimes(CacheLogicForUser::class, 2);
        Bus::assertNotDispatched(CacheLogicForGroup::class);
        Bus::assertNotDispatched(CacheLogicForRole::class);
        Bus::assertNotDispatched(CacheLogicForSingleCombination::class);
    }

    /** @test */
    public function it_dispatches_a_CacheLogicForGroup_if_the_model_is_a_group(){
        Bus::fake([CacheLogic::class, CacheLogicForGroup::class, CacheLogicForGroup::class, CacheLogicForRole::class, CacheLogicForSingleCombination::class]);

        $filterInstances = FilterInstance::factory()->count(2)->createQuietly();

        $listener = new RefreshLogicResult();
        $listener->handle(new AudienceChanged($filterInstances->all(), $this->newGroup()));

        Bus::assertNotDispatched(CacheLogic::class);
        Bus::assertNotDispatched(CacheLogicForUser::class);
        Bus::assertDispatchedTimes(CacheLogicForGroup::class, 2);
        Bus::assertNotDispatched(CacheLogicForRole::class);
        Bus::assertNotDispatched(CacheLogicForSingleCombination::class);
    }

    /** @test */
    public function it_dispatches_a_CacheLogicForRole_if_the_model_is_a_role(){
        Bus::fake([CacheLogic::class, CacheLogicForRole::class, CacheLogicForGroup::class, CacheLogicForRole::class, CacheLogicForSingleCombination::class]);

        $filterInstances = FilterInstance::factory()->count(2)->createQuietly();

        $listener = new RefreshLogicResult();
        $listener->handle(new AudienceChanged($filterInstances->all(), $this->newRole()));

        Bus::assertNotDispatched(CacheLogic::class);
        Bus::assertNotDispatched(CacheLogicForUser::class);
        Bus::assertNotDispatched(CacheLogicForGroup::class);
        Bus::assertDispatchedTimes(CacheLogicForRole::class, 2);
        Bus::assertNotDispatched(CacheLogicForSingleCombination::class);
    }

    /** @test */
    public function it_only_dispatches_for_filters_with_a_logic(){
        Bus::fake([CacheLogic::class, CacheLogicForRole::class, CacheLogicForGroup::class, CacheLogicForRole::class, CacheLogicForSingleCombination::class]);

        $filterInstance1 = FilterInstance::factory()->createQuietly();
        $filterInstance2 = FilterInstance::factory()->createQuietly(['logic_id' => null, 'logic_type' => null]);

        $listener = new RefreshLogicResult();
        $listener->handle(new AudienceChanged([$filterInstance1, $filterInstance2], $this->newRole()));

        Bus::assertNotDispatched(CacheLogic::class);
        Bus::assertNotDispatched(CacheLogicForUser::class);
        Bus::assertNotDispatched(CacheLogicForGroup::class);
        Bus::assertDispatchedTimes(CacheLogicForRole::class, 1);
        Bus::assertNotDispatched(CacheLogicForSingleCombination::class);
    }

}

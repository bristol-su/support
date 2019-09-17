<?php


namespace BristolSU\Support\Tests\Filters;


use BristolSU\Support\Filters\FilterInstance;
use BristolSU\Support\Filters\FilterInstanceRepository;
use BristolSU\Support\Tests\TestCase;

class FilterInstanceRepositoryTest extends TestCase
{

    /** @test */
    public function it_creates_a_filter_instance(){
        $repository = new FilterInstanceRepository;
        $repository->create([
            'alias' => 'alias1',
            'name' => 'filterName',
            'settings' => ['some' => 'option']
        ]);

        $this->assertDatabaseHas('filter_instances', [
            'alias' => 'alias1',
            'name' => 'filterName',
            'settings' => json_encode(['some' => 'option'])
        ]);
    }

    /** @test **/
    public function all_returns_all_filter_instances()
    {
        $filterInstance1 = factory(FilterInstance::class)->create();
        $filterInstance2 = factory(FilterInstance::class)->create();
        $filterInstance3 = factory(FilterInstance::class)->create();
        $repository = new FilterInstanceRepository;
        $filterInstances = $repository->all();

        $this->assertModelEquals($filterInstance1, $filterInstances[0]);
        $this->assertModelEquals($filterInstance2, $filterInstances[1]);
        $this->assertModelEquals($filterInstance3, $filterInstances[2]);

    }

}

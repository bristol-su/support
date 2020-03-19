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

    /** @test */
    public function getById_returns_a_filter_instance_by_id(){
        $filterInstance = factory(FilterInstance::class)->create();
        factory(FilterInstance::class, 5)->create();

        $repository = new FilterInstanceRepository;
        $resolvedFilterInstance = $repository->getById($filterInstance->id);
        
        $this->assertInstanceOf(FilterInstance::class, $resolvedFilterInstance);
        $this->assertModelEquals($filterInstance, $resolvedFilterInstance);
    }
    
    /** @test */
    public function delete_deletes_the_filter_instance(){
        $filterInstance = factory(FilterInstance::class)->create();
        factory(FilterInstance::class, 5)->create();

        $this->assertDatabaseHas('filter_instances', ['id' => $filterInstance->id]);
        
        $repository = new FilterInstanceRepository;
        $repository->delete($filterInstance->id);
        
        $this->assertDatabaseMissing('filter_instances', ['id' => $filterInstance->id]);
    }
    
    /** @test */
    public function update_updates_a_filter_instance(){
        $filterInstance = factory(FilterInstance::class)->create([
            'alias' => 'OldAlias', 'name' => 'OldName', 'settings' => ['val' => 'OldVal']
        ]);
        factory(FilterInstance::class, 5)->create();

        $this->assertDatabaseHas('filter_instances', [
            'id' => $filterInstance->id, 'alias' => 'OldAlias', 'name' => 'OldName', 'settings' => json_encode(['val' => 'OldVal'])
        ]);

        $repository = new FilterInstanceRepository;
        $repository->update($filterInstance->id, [
            'alias' => 'NewAlias', 'name' => 'NewName', 'settings' => ['val' => 'NewVal']
        ]);

        $this->assertDatabaseHas('filter_instances', [
            'id' => $filterInstance->id, 'alias' => 'NewAlias', 'name' => 'NewName', 'settings' => json_encode(['val' => 'NewVal'])
        ]);
        
    }
    
}

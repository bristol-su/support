<?php

namespace BristolSU\Support\Tests\ActivityInstance\AuthenticationProvider;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ActivityInstance\AuthenticationProvider\ActivityInstanceProvider;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceRepository;
use BristolSU\Support\Tests\TestCase;
use BristolSU\Support\User\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ActivityInstanceProviderTest extends TestCase
{
    /** @test */
    public function retrieve_by_id_returns_the_activity_instance()
    {
        $activityInstance = factory(ActivityInstance::class)->create();
        $activityInstanceRepository = $this->prophesize(ActivityInstanceRepository::class);
        $activityInstanceRepository->getById($activityInstance->id)->shouldBeCalled()->willReturn($activityInstance);
            
        $provider = new ActivityInstanceProvider($activityInstanceRepository->reveal());
        $this->assertModelEquals($activityInstance, $provider->retrieveById($activityInstance->id));
    }
 
    /** @test */
    public function retrieve_by_credentials_returns_the_activity_instance_if_found()
    {
        $activityInstance = factory(ActivityInstance::class)->create();
        $activityInstanceRepository = $this->prophesize(ActivityInstanceRepository::class);
        $activityInstanceRepository->getById($activityInstance->id)->shouldBeCalled()->willReturn($activityInstance);

        $provider = new ActivityInstanceProvider($activityInstanceRepository->reveal());
        $this->assertModelEquals($activityInstance, $provider->retrieveByCredentials(['activity_instance_id' => $activityInstance->id]));
    }

    /** @test */
    public function retrieve_by_credentials_returns_null_if_activity_instance_not_given()
    {
        $activityInstanceRepository = $this->prophesize(ActivityInstanceRepository::class);

        $provider = new ActivityInstanceProvider($activityInstanceRepository->reveal());
        $this->assertNull($provider->retrieveByCredentials(['activity_instance_id_not' => 100]));
    }

    /** @test */
    public function retrieve_by_credentials_returns_null_if_activity_instance_repository_throws_exception()
    {
        $activityInstanceRepository = $this->prophesize(ActivityInstanceRepository::class);
        $activityInstanceRepository->getById(100)->shouldBeCalled()->willThrow(new ModelNotFoundException());

        $provider = new ActivityInstanceProvider($activityInstanceRepository->reveal());
        $this->assertNull($provider->retrieveByCredentials(['activity_instance_id' => 100]));
    }

    /** @test */
    public function validate_credentials_returns_true_if_activity_instance_found()
    {
        $user = factory(User::class)->create();
        $activityInstance = factory(ActivityInstance::class)->create();
        $activityInstanceRepository = $this->prophesize(ActivityInstanceRepository::class);
        $activityInstanceRepository->getById($activityInstance->id)->shouldBeCalled()->willReturn($activityInstance);

        $provider = new ActivityInstanceProvider($activityInstanceRepository->reveal());
        $this->assertTrue($provider->validateCredentials($user, ['activity_instance_id' => $activityInstance->id]));
    }

    /** @test */
    public function validate_credentials_returns_false_if_activity_instance_not_given()
    {
        $user = factory(User::class)->create();
        $activityInstanceRepository = $this->prophesize(ActivityInstanceRepository::class);

        $provider = new ActivityInstanceProvider($activityInstanceRepository->reveal());
        $this->assertFalse($provider->validateCredentials($user, ['activity_instance_id_not' => 100]));
    }

    /** @test */
    public function validate_credentials_returns_false_if_activity_instance_repository_throws_exception()
    {
        $user = factory(User::class)->create();
        $activityInstanceRepository = $this->prophesize(ActivityInstanceRepository::class);
        $activityInstanceRepository->getById(100)->shouldBeCalled()->willThrow(new ModelNotFoundException());

        $provider = new ActivityInstanceProvider($activityInstanceRepository->reveal());
        $this->assertFalse($provider->validateCredentials($user, ['activity_instance_id' => 100]));
    }

    /** @test */
    public function retrieve_by_token_returns_null()
    {
        $activityInstanceRepository = $this->prophesize(ActivityInstanceRepository::class);
        $provider = new ActivityInstanceProvider($activityInstanceRepository->reveal());
        $this->assertNull($provider->retrieveByToken(1, 'token'));
    }

    /** @test */
    public function update_remember_token_returns_null()
    {
        $user = factory(User::class)->create();
        $activityInstanceRepository = $this->prophesize(ActivityInstanceRepository::class);

        $provider = new ActivityInstanceProvider($activityInstanceRepository->reveal());
        $this->assertNull($provider->updateRememberToken($user, 'token'));
    }
}

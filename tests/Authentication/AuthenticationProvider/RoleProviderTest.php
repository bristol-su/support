<?php


namespace BristolSU\Support\Tests\Authentication\AuthenticationProvider;


use BristolSU\Support\Authentication\AuthenticationProvider\RoleProvider;
use BristolSU\ControlDB\Contracts\Repositories\Role as RoleRepositoryContract;
use BristolSU\ControlDB\Models\Role;
use BristolSU\Support\User\User;
use BristolSU\Support\Tests\TestCase;

class RoleProviderTest extends TestCase
{

    /** @test */
    public function retrieve_by_id_retrieves_a_role_by_id(){
        $role = $this->newRole();
        $roleRepository = $this->prophesize(RoleRepositoryContract::class);
        $roleRepository->getById($role->id())->shouldBeCalled()->willReturn($role);

        $roleProvider = new RoleProvider($roleRepository->reveal());
        $this->assertModelEquals($role, $roleProvider->retrieveById($role->id()));
    }

    /** @test */
    public function retrieve_by_credentials_retrieves_a_role_by_credentials(){
        $role = $this->newRole();
        $roleRepository = $this->prophesize(RoleRepositoryContract::class);
        $roleRepository->getById($role->id())->shouldBeCalled()->willReturn($role);

        $roleProvider = new RoleProvider($roleRepository->reveal());
        $this->assertModelEquals($role, $roleProvider->retrieveByCredentials(['role_id' => $role->id()]));
    }

    /** @test */
    public function retrieve_by_credentials_returns_null_if_role_id_not_set(){
        $roleRepository = $this->prophesize(RoleRepositoryContract::class);

        $roleProvider = new RoleProvider($roleRepository->reveal());
        $this->assertNull($roleProvider->retrieveByCredentials([]));
    }

    /** @test */
    public function validate_credentials_returns_false_if_role_id_not_found(){
        $roleRepository = $this->prophesize(RoleRepositoryContract::class);
        $roleRepository->getById(1)->shouldBeCalled()->willThrow(new \Exception);
        $user = $this->newUser();

        $roleProvider = new RoleProvider($roleRepository->reveal());
        $this->assertFalse($roleProvider->validateCredentials($user, ['role_id' => 1]));
    }

}

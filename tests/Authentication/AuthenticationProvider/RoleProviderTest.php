<?php


namespace BristolSU\Support\Tests\Authentication\AuthenticationProvider;


use BristolSU\Support\Authentication\AuthenticationProvider\RoleProvider;
use BristolSU\ControlDB\Contracts\Repositories\Role as RoleRepositoryContract;
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
    public function validate_credentials_returns_false_if_role_id_not_given(){
        $roleRepository = $this->prophesize(RoleRepositoryContract::class);
        $role = $this->newRole();

        $roleProvider = new RoleProvider($roleRepository->reveal());
        $this->assertFalse($roleProvider->validateCredentials($role, []));
    }

    /** @test */
    public function validate_credentials_returns_false_if_role_id_not_found(){
        $roleRepository = $this->prophesize(RoleRepositoryContract::class);
        $roleRepository->getById(1)->shouldBeCalled()->willThrow(new \Exception);
        $role = $this->newRole();

        $roleProvider = new RoleProvider($roleRepository->reveal());
        $this->assertFalse($roleProvider->validateCredentials($role, ['role_id' => 1]));
    }

    /** @test */
    public function validate_credentials_returns_true_if_role_id_found(){
        $role = $this->newRole();
        $roleRepository = $this->prophesize(RoleRepositoryContract::class);
        $roleRepository->getById($role->id())->shouldBeCalled()->willReturn($role);

        $roleProvider = new RoleProvider($roleRepository->reveal());
        $this->assertTrue($roleProvider->validateCredentials($role, ['role_id' => $role->id]));
    }

    /** @test */
    public function retrieveByToken_always_returns_null(){
        $roleRepository = $this->prophesize(RoleRepositoryContract::class);
        $roleProvider = new RoleProvider($roleRepository->reveal());

        $this->assertNull($roleProvider->retrieveByToken(1, 'fff'));

    }

    /** @test */
    public function updateRememberToken_always_returns_null(){
        $role = $this->newRole();
        $roleRepository = $this->prophesize(RoleRepositoryContract::class);
        $roleProvider = new RoleProvider($roleRepository->reveal());

        $this->assertNull($roleProvider->updateRememberToken($role, 'fff'));
    }

}

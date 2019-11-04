<?php


namespace BristolSU\Support\Tests\Control\Repositories;


use BristolSU\Support\Control\Contracts\Client\Client;
use BristolSU\Support\Control\Contracts\Models\User as UserContract;
use BristolSU\Support\Control\Repositories\User;
use Illuminate\Support\Collection;
use Prophecy\Argument;
use BristolSU\Support\Tests\TestCase;

class UserTest extends TestCase
{

    /** @test */
    public function find_or_create_by_data_id_returns_a_user_if_found(){
        $this->mockControl('post', 'students/search', [['uc_uid'=>2845746]]);

        $userRepository = new User($this->controlClient->reveal());
        $user = $userRepository->findOrCreateByDataId(2845746);

        $this->assertEquals(2845746, $user->dataPlatformId());
    }

    /** @test */
    public function find_or_create_by_data_id_creates_a_control_user_if_not_found(){
        $client = $this->prophesize(Client::class);
        $client->request('post', 'students', Argument::that(function($arg){
            return $arg['form_params'] === ['uc_uid' => 2845746];
        }))->shouldBeCalled()->willReturn(['uc_uid' => 2845746]);

        $userRepository = new User($client->reveal());
        $user = $userRepository->findOrCreateByDataId(2845746);

        $this->assertEquals(2845746, $user->dataPlatformId());
    }
    
    /** @test */
    public function find_by_data_id_finds_by_data_id(){
        $this->mockControl('post', 'students/search', [['uc_uid'=>2845746]]);

        $userRepository = new User($this->controlClient->reveal());
        $user = $userRepository->findByDataId(2845746);

        $this->assertEquals(2845746, $user->dataPlatformId());
    }
    
    /** @test */
    public function getById_returns_a_user_by_id(){
        $this->mockControl('get', 'students/10', ['uc_uid'=>2845746]);

        $userRepository = new User($this->controlClient->reveal());
        $user = $userRepository->getById(10);

        $this->assertEquals(2845746, $user->dataPlatformId());
    }
    
    /** @test */
    public function all_returns_all_users_as_a_collection(){
        $this->mockControl('get', 'students', [['uc_uid'=>1], ['uc_uid'=>2], ['uc_uid'=>3]]);

        $userRepository = new User($this->controlClient->reveal());
        $users = $userRepository->all();
        
        $this->assertInstanceOf(Collection::class, $users);
        $this->assertCount(3, $users);
        $this->assertInstanceOf(UserContract::class, $users[0]);
        $this->assertEquals(1, $users[0]->dataPlatformId());
        $this->assertInstanceOf(UserContract::class, $users[1]);
        $this->assertEquals(2, $users[1]->dataPlatformId());
        $this->assertInstanceOf(UserContract::class, $users[2]);
        $this->assertEquals(3, $users[2]->dataPlatformId());
    }

}

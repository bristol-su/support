<?php

namespace BristolSU\Support\Tests\Authentication\AuthQuery;

use BristolSU\Support\Authentication\AuthQuery\AuthCredentials;
use BristolSU\Support\Authentication\AuthQuery\Generator;
use BristolSU\Support\Tests\TestCase;

class AuthCredentialsTest extends TestCase
{
    /** @test */
    public function it_can_be_created()
    {
        $authCredentials = new AuthCredentials(5, 3, 1);
        $this->assertInstanceOf(AuthCredentials::class, $authCredentials);
    }

    /** @test */
    public function to_array_converts_the_object_to_an_array()
    {
        $authCredentials = new AuthCredentials(5, 3, 1);
        $this->assertEquals([
            'g' => 5,
            'r' => 3,
            'a' => 1
        ], $authCredentials->toArray());
    }

    /** @test */
    public function to_array_filters_out_null_values()
    {
        $authCredentials = new AuthCredentials(5, null, 1);
        $this->assertEquals([
            'g' => 5,
            'a' => 1
        ], $authCredentials->toArray());
    }

    /** @test */
    public function to_query_converts_the_object_to_a_query_string()
    {
        $authCredentials = new AuthCredentials(5, 3, 1);
        $this->assertEquals('g=5&r=3&a=1', $authCredentials->toQuery());
    }

    /** @test */
    public function to_query_filters_out_null_values()
    {
        $authCredentials = new AuthCredentials(null, 3, 1);
        $this->assertEquals('r=3&a=1', $authCredentials->toQuery());
    }

    /** @test */
    public function to_string_converts_the_object_to_json()
    {
        $authCredentials = new AuthCredentials(5, 3, 1);
        $this->assertEquals(json_encode([
            'g' => 5,
            'r' => 3,
            'a' => 1
        ]), $authCredentials->toString());
    }

    /** @test */
    public function to_string_filters_out_null_values()
    {
        $authCredentials = new AuthCredentials(5, 3, null);
        $this->assertEquals(json_encode([
            'g' => 5,
            'r' => 3,
        ]), $authCredentials->toString());
    }

    /** @test */
    public function to_json_converts_the_object_to_json()
    {
        $authCredentials = new AuthCredentials(5, 3, 1);
        $this->assertEquals(json_encode([
            'g' => 5,
            'r' => 3,
            'a' => 1
        ]), $authCredentials->toJson());
    }

    /** @test */
    public function to_json_filters_out_null_values()
    {
        $authCredentials = new AuthCredentials(5, 3, null);
        $this->assertEquals(json_encode([
            'g' => 5,
            'r' => 3,
        ]), $authCredentials->toJson());
    }

    /** @test */
    public function casting_to_string_converts_the_object_to_json()
    {
        $authCredentials = new AuthCredentials(5, 3, 1);
        $this->assertEquals(json_encode([
            'g' => 5,
            'r' => 3,
            'a' => 1
        ]), (string) $authCredentials);
    }

    /** @test */
    public function casting_to_string_filters_out_null_values()
    {
        $authCredentials = new AuthCredentials(5, 3, null);
        $this->assertEquals(json_encode([
            'g' => 5,
            'r' => 3,
        ]), (string) $authCredentials);
    }

    /** @test */
    public function group_id_gives_the_group_id_or_null_if_group_id_is_null()
    {
        $authCredentialsNotNull = new AuthCredentials(5, null, null);
        $authCredentialsNull = new AuthCredentials(null, null, null);

        $this->assertEquals(5, $authCredentialsNotNull->groupId());
        $this->assertNull($authCredentialsNull->groupId());
    }

    /** @test */
    public function role_id_gives_the_role_id_or_null_if_role_id_is_null()
    {
        $authCredentialsNotNull = new AuthCredentials(null, 1, null);
        $authCredentialsNull = new AuthCredentials(null, null, null);

        $this->assertEquals(1, $authCredentialsNotNull->roleId());
        $this->assertNull($authCredentialsNull->roleId());
    }

    /** @test */
    public function activity_instance_id_gives_the_activity_instance_id_or_null_if_activity_instance_id_is_null()
    {
        $authCredentialsNotNull = new AuthCredentials(null, null, 5000);
        $authCredentialsNull = new AuthCredentials(null, null, null);

        $this->assertEquals(5000, $authCredentialsNotNull->activityInstanceId());
        $this->assertNull($authCredentialsNull->activityInstanceId());
    }

    /** @test */
    public function urlGenerator_getAuthQueryArray_gets_the_query_array()
    {
        $credentials = $this->prophesize(AuthCredentials::class);
        $credentials->toArray()->shouldBeCalled()->willReturn(['u' => 1]);
        $generator = $this->prophesize(Generator::class);
        $generator->getAuthCredentials()->shouldBeCalled()->willReturn($credentials->reveal());
        $this->instance(Generator::class, $generator->reveal());

        $this->assertEquals([
            'u' => 1
        ], url()->getAuthQueryArray());
    }

    /** @test */
    public function urlGenerator_getAuthQueryString_gets_the_query_string_as_a_query()
    {
        $credentials = $this->prophesize(AuthCredentials::class);
        $credentials->toQuery()->shouldBeCalled()->willReturn('u=1&g=4&a=2');
        $generator = $this->prophesize(Generator::class);
        $generator->getAuthCredentials()->shouldBeCalled()->willReturn($credentials->reveal());
        $this->instance(Generator::class, $generator->reveal());

        $this->assertEquals('u=1&g=4&a=2', url()->getAuthQueryString());
    }
}

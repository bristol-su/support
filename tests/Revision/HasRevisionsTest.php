<?php

namespace BristolSU\Support\Tests\Revision;

use BristolSU\Support\Revision\HasRevisions;
use BristolSU\Support\Tests\TestCase;

class HasRevisionsTest extends TestCase
{
    use HasRevisions;

    /** @test */
    public function it_initializes_properties_from_the_config(){
        $config = app('config');
        $config->set('support.revision.cleanup.enabled', false);
        $config->set('support.revision.cleanup.limit', 5);
        
        $this->initializeHasRevisions();

        $this->assertEquals(false, $this->revisionCleanup);
        $this->assertEquals(5, $this->historyLimit);
    }
    
    /** @test */
    public function it_returns_the_ID_of_a_user_through_authentication(){
        $user = $this->newUser();
        $this->beUser($user);
        
        $this->assertEquals($user->id(), $this->getSystemUserId());
    }
    
    /** @test */
    public function it_returns_null_if_no_user_found(){
        $this->assertNull($this->getSystemUserId());
    }
    
}
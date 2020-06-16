<?php

namespace BristolSU\Support\Tests\Progress\Handlers\AirTable;

use BristolSU\Support\Progress\Handlers\AirTable\CreateRecords;
use BristolSU\Support\Tests\TestCase;
use GuzzleHttp\Client;

class CreateRecordsTest extends TestCase
{

    /** @test */
    public function the_correct_api_call_is_executed(){
        $data = ['records' => [
            'fields' => ['test' => '123']
        ], 'typecast' => true];
        $job = new CreateRecords($data, 'myApiKey1', 'myBaseId1', 'myTableName1');
        
        $client = $this->prophesize(Client::class);
        $client->post('https://api.airtable.com/v0/myBaseId1/myTableName1', [
            'json' => $data,
            'headers' => [
                'Authorization' => 'Bearer myApiKey1'
            ]
        ])->shouldBeCalled();
        
        $job->handle($client->reveal());
    }
    
}
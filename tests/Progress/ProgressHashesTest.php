<?php

namespace BristolSU\Support\Tests\Progress;

use BristolSU\Support\Progress\ProgressHashes;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class ProgressHashesTest extends TestCase
{
    use WithFaker;

    protected $Model;
    protected $Table;

    public function setUp(): void
    {
        parent::setUp();

        $this->Model = (new ProgressHashes);
        $this->Table = $this->Model->getTable();
    }

    public function getModelData()
    {
        return [
            'item_key' => $this->faker('en_UK')->slug,
            'hash' => $this->faker('en_UK')->uuid
        ];
    }

    /** @test */
    public function a_model_can_be_saved_and_retrieved_by_the_hash(){
        $data = $this->getModelData();

        $this->Model->create($data);

        $this->assertDatabaseHas($this->Table, $data);

        $dbValue = $this->Model->getByHash($data['hash'])->first(['item_key', 'hash'])->toArray();

        $this->assertEquals($data, $dbValue);
    }

    /** @test */
    public function a_model_can_be_saved_and_retrieved_by_the_item_id(){
        $data = $this->getModelData();

        $this->Model->create($data);

        $this->assertDatabaseHas($this->Table, $data);

        $dbValue = $this->Model->find($data['item_key'])->first(['item_key', 'hash'])->toArray();

        $this->assertEquals($data, $dbValue);
    }
}
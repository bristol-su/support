<?php

namespace BristolSU\Support\Tests\Progress;

use BristolSU\Support\Progress\ProgressHash;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;

class ProgressHashTest extends TestCase
{
    use WithFaker;

    protected $Model;

    protected $Table;

    public function setUp(): void
    {
        parent::setUp();

        $this->Model = (new ProgressHash());
        $this->Table = $this->Model->getTable();
    }

    public function getModelData()
    {
        return [
            'item_key' => 'caller_1',
            'hash' => Hash::make('item_hash')
        ];
    }

    /** @test */
    public function a_model_can_be_saved_and_retrieved_by_the_hash()
    {
        $data = $this->getModelData();

        $this->Model->create($data);

        $this->assertDatabaseHas($this->Table, $data);

        $dbValue = $this->Model->byHash($data['hash'])->first(['item_key', 'hash'])->toArray();

        $this->assertEquals($data, $dbValue);
    }

    /** @test */
    public function a_model_can_be_saved_and_retrieved_by_the_item_id()
    {
        $data = $this->getModelData();

        $Model = $this->Model->create($data);

        $this->assertDatabaseHas($this->Table, $data);

        $dbValue = $this->Model->find($data['item_key'])->first(['item_key', 'hash'])->toArray();

        $this->assertEquals($data, $dbValue);
    }

    /** @test */
    public function get_item_key_returns_the_item_key()
    {
        $progressHash = factory(ProgressHash::class)->create([
            'item_key' => '1_199'
        ]);

        $this->assertDatabaseHas('progress_change_hashes', [
            'item_key' => '1_199'
        ]);

        $this->assertEquals('1_199', $progressHash->getItemKey());
    }

    /** @test */
    public function get_hash_returns_the_hash()
    {
        $progressHash = factory(ProgressHash::class)->create([
            'hash' => 'kjflhskdfjhlakfcjbelcjhbwdbja'
        ]);

        $this->assertDatabaseHas('progress_change_hashes', [
            'hash' => 'kjflhskdfjhlakfcjbelcjhbwdbja'
        ]);

        $this->assertEquals('kjflhskdfjhlakfcjbelcjhbwdbja', $progressHash->getHash());
    }
}

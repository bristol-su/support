<?php

namespace BristolSU\Support\Progress\Handlers\AirTable;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreateRecords implements ShouldQueue
{
    use Dispatchable, Queueable;

    private array $data;
    private string $apiKey;
    private string $baseId;
    private string $tableName;

    public function __construct(array $data, string $apiKey, string $baseId, string $tableName)
    {
        $this->data = $data;
        $this->apiKey = $apiKey;
        $this->baseId = $baseId;
        $this->tableName = $tableName;
    }

    public function handle(Client $client)
    {
        $client->post(
            sprintf('https://api.airtable.com/v0/%s/%s', $this->baseId, $this->tableName),
            [
                'json' => $this->data,
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey
                ]
            ]
        );
    }

}
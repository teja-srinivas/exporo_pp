<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use function GuzzleHttp\json_decode;
use Illuminate\Console\Command;

abstract class ImportCommand extends Command
{
    /**
     * The importable model we're using.
     * @var string
     */
    protected $model;

    /**
     * The URL to the API enpoint
     * @var string
     */
    protected $apiUrl;


    public function handle()
    {
        $updated_at = $this->argument('updated_at') ?: $this->model::getNewestUpdatedAtDate();

        $nextLink = $this->apiUrl . '?api-token=12341&updated_at=' . $updated_at;
        $client = new Client();

        while ($nextLink) {
            $response = json_decode($client->get($nextLink)->getBody()->getContents(), true);
            $this->importModels($response['items']);
            $nextLink = $response['next'];
        }
    }

    private function importModels(array $models): void
    {
        foreach ($models['items'] as $model) {
            if ($model['created_at'] === '0000-00-00 00:00:00') {
                continue;
            }

            $this->importModel($model);
        }
    }

    abstract protected function importModel(array $data): void;
}

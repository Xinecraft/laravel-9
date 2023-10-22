<?php

namespace App\Utilities\Elasticsearch;

use App\Utilities\Contracts\ElasticsearchHelperInterface;
use Illuminate\Support\Carbon;

class ElasticsearchHelper implements ElasticsearchHelperInterface
{
    protected $elasticsearchClient;

    public function __construct(ElasticsearchClient $elasticsearchClient)
    {
        $this->elasticsearchClient = $elasticsearchClient->getClient();
    }

    /**
     * Store the email's message body, subject, and to address inside Elasticsearch.
     *
     * @return mixed - Return the id of the record inserted into Elasticsearch
     */
    public function storeEmail(string $messageBody, string $messageSubject, string $toEmailAddress, string $index): mixed
    {
        $params = [
            'index' => $index,
        ];
        if (! $this->elasticsearchClient->indices()->exists($params)) {
            // Create the index if it doesn't exist
            $this->elasticsearchClient->indices()->create($params);
        }

        $document = [
            'email' => $toEmailAddress,
            'subject' => $messageSubject,
            'body' => $messageBody,
            'timestamp' => Carbon::now()->toISOString(),
        ];

        $response = $this->elasticsearchClient->index([
            'index' => $index,
            'body' => $document,
        ]);

        return $response['_id'];
    }

    public function listEmails($index)
    {
        $params = [
            'index' => $index,
            'body' => [
                'query' => [
                    'match_all' => new \stdClass(),
                ],
            ],
        ];

        try {
            $response = $this->elasticsearchClient->search($params);
            $hits = $response['hits']['hits'];

            $data = [];
            foreach ($hits as $hit) {
                $data[] = [
                    'id' => $hit['_id'],
                    'email' => $hit['_source']['email'],
                    'subject' => $hit['_source']['subject'],
                    'body' => $hit['_source']['body'],
                    'timestamp' => $hit['_source']['timestamp'],
                ];
            }

            return $data;

        } catch (\Exception $e) {
            return [];
        }
    }
}

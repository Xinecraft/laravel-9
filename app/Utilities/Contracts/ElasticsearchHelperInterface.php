<?php

namespace App\Utilities\Contracts;

interface ElasticsearchHelperInterface
{
    /**
     * Store the email's message body, subject and to address inside elasticsearch.
     *
     * @return mixed - Return the id of the record inserted into Elasticsearch
     */
    public function storeEmail(string $messageBody, string $messageSubject, string $toEmailAddress, string $index): mixed;
}

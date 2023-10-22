<?php

namespace App\Utilities\Contracts;

interface RedisHelperInterface
{
    /**
     * Store the id of a message along with a message subject in Redis.
     */
    public function storeRecentMessage(mixed $id, string $messageSubject, string $toEmailAddress, string $userId): void;
}

<?php

namespace App\Utilities\Redis;

use App\Utilities\Contracts\RedisHelperInterface;
use Illuminate\Support\Facades\Redis;

class RedisHelper implements RedisHelperInterface
{
    public function storeRecentMessage(mixed $id, string $messageSubject, string $toEmailAddress, $userId): void
    {
        $data = json_encode([
            'id' => $id,
            'subject' => $messageSubject,
            'email' => $toEmailAddress,
        ]);
        Redis::set('emails::'.$userId.'::'.$id, $data);
    }
}

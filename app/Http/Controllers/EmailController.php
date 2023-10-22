<?php

namespace App\Http\Controllers;

use App\Jobs\SendGenericEmailJob;
use App\Utilities\Contracts\ElasticsearchHelperInterface;
use App\Utilities\Contracts\RedisHelperInterface;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function send(Request $request, $user)
    {
        $request->validate([
            'data' => 'required|array|max:100',
            'data.*.subject' => 'required|string',
            'data.*.body' => 'required|string',
            'data.*.email' => 'required|email',
        ]);

        $authUser = $request->user();
        $data = $request->input('data');

        $elasticsearchHelper = app()->make(ElasticsearchHelperInterface::class);
        $redisHelper = app()->make(RedisHelperInterface::class);
        foreach ($data as $email) {
            $id = $elasticsearchHelper->storeEmail($email['body'], $email['subject'], $email['email'], 'user_'.$authUser->id);
            $redisHelper->storeRecentMessage($id, $email['subject'], $email['email'], $authUser->id);

            // Dispatch a job to send the email
            SendGenericEmailJob::dispatch($email['email'], $email['subject'], $email['body']);
        }

        return response()->json([
            'message' => 'Emails sent successfully',
        ]);
    }

    public function list(Request $request, $user)
    {
        $authUser = $request->user();

        $elasticsearchHelper = app()->make(ElasticsearchHelperInterface::class);
        $list = $elasticsearchHelper->listEmails('user_'.$authUser->id);

        return response()->json([
            'data' => $list,
        ]);
    }
}

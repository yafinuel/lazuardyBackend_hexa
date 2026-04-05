<?php

namespace App\Domains\Notification\Infrastructure\Jobs;

use App\Domains\Notification\Actions\SendPushNotificationAction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendPushNotificationJob implements ShouldQueue
{
    use Queueable;

    protected string $token;
    protected string $title;
    protected string $body;
    protected array $data;

    public function __construct(string $token, string $title, string $body, array $data = [])
    {
        $this->token = $token;
        $this->title = $title;
        $this->body = $body;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(SendPushNotificationAction $action): void
    {
        $action->execute(
            $this->token,
            $this->title,
            $this->body,
            $this->data
        );
    }
}

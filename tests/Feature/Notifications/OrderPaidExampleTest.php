<?php

namespace Tests\Feature\Notifications;

use App\Domains\Notification\Notifications\OrderPaidExample;
use App\Domains\Notification\Ports\NotificationGatewayInterface;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class OrderPaidExampleTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_order_paid_notification()
    {
        $this->app->bind(NotificationGatewayInterface::class, fn () => new class implements NotificationGatewayInterface {
            public function sendPush(string $token, string $title, string $body, array $data = []): bool
            {
                return true;
            }
        });

        $user = User::factory()->create([
            'fcm_token' => null,
        ]);

        $details = [
            'title' => 'Pembayaran Berhasil',
            'body' => 'Order kamu sudah terbayar.',
            'data' => [
                'order_id' => 'INV-001',
                'status' => 'paid',
            ],
            'order_id' => 'INV-001',
        ];

        $user->notify(new OrderPaidExample($details));

        $this->assertDatabaseCount('notifications', 1);

        $savedNotification = $user->notifications()->first();

        $this->assertNotNull($savedNotification);
        $this->assertSame(OrderPaidExample::class, $savedNotification->type);
        $this->assertSame([
            'title' => 'Pembayaran Berhasil',
            'body' => 'Order kamu sudah terbayar.',
            'data' => [
                'order_id' => 'INV-001',
                'status' => 'paid',
            ],
        ], $savedNotification->data);
    }

    #[Test]
    public function it_uses_default_database_payload_when_details_are_missing()
    {
        $notifiable = User::factory()->make();
        $notification = new OrderPaidExample([]);

        $this->assertSame([
            'title' => 'Order Paid',
            'body' => 'Your order has been paid successfully.',
            'data' => [],
        ], $notification->toDatabase($notifiable));
    }

    #[Test]
    public function it_builds_expected_fcm_payload()
    {
        $notifiable = User::factory()->make();
        $notification = new OrderPaidExample([
            'order_id' => 123,
        ]);

        $this->assertSame([
            'title' => 'Pembayaran Berhasil',
            'body' => 'Klik untuk lihat detail paket kamu.',
            'data' => [
                'type' => 'payment_success',
                'id' => '123',
            ],
        ], $notification->toFcm($notifiable));
    }

    #[Test]
    public function it_uses_correct_channels()
    {
        $notification = new OrderPaidExample([]);
        $channels = $notification->via(User::factory()->make());

        $this->assertContains('database', $channels);
        $this->assertContains(\App\Domains\Notification\Infrastructure\External\Firebase\FcmChannel::class, $channels);
    }
}

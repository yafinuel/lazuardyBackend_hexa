<?php

namespace Tests\Unit\Penalty;

use App\Domains\Penalty\Actions\GetUserWarningAction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PenaltyTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function test_get_user_warning(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-04-18 10:00:00'));
        $action = app(GetUserWarningAction::class);

        $cases = [
            ['warning' => 0, 'sanction' => null, 'expected' => 0],
            ['warning' => 3, 'sanction' => Carbon::now()->copy()->addDays(7), 'expected' => 3],
            ['warning' => 6, 'sanction' => Carbon::now()->copy()->subDays(1), 'expected' => 0],
            ['warning' => 1, 'sanction' => null, 'expected' => 1],
            ['warning' => 5, 'sanction' => Carbon::now()->copy()->subDays(7), 'expected' => 2],
            ['warning' => 7, 'sanction' => Carbon::now()->copy()->subDays(1), 'expected' => 1],
        ];

        foreach ($cases as $index => $case) {
            $user = User::factory()->create([
                'warning' => $case['warning'],
                'sanction' => $case['sanction'],
            ]);

            $result = $action->execute($user->id);

            $this->assertSame($case['expected'], $result, "Failed on case #{$index}");
        }

        Carbon::setTestNow();
    }
}

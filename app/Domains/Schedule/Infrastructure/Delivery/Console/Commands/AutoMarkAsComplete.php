<?php

namespace App\Domains\Schedule\Infrastructure\Delivery\Console\Commands;

use App\Domains\Schedule\Actions\MarkAsCompleteScheduleAction;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoMarkAsComplete extends Command
{
    protected $signature = 'app:AutoMarkAsComplete';

    protected $description = 'Command description';
    
    public function __construct(
        protected MarkAsCompleteScheduleAction $markAsCompleteScheduleAction,
    )
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $boundary = Carbon::now()->subHours(12);

        $schedule = Schedule::where('status', 'reported')
            ->where('updated_at', '<=', $boundary)
            ->get();

        foreach ($schedule as $item) {
            $this->markAsCompleteScheduleAction->execute($item->student_id, $item->id);
        }
    }
}
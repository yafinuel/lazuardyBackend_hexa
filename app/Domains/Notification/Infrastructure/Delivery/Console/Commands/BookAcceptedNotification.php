<?php

namespace App\Domains\Notification\Infrastructure\Delivery\Console\Commands;

use Illuminate\Console\Command;

class BookAcceptedNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:one-hour-student-schedule-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
    }
}
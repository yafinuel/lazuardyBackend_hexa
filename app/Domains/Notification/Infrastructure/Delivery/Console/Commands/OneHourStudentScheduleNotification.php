<?php

namespace App\Domains\Notification\Infrastructure\Delivery\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class OneHourStudentScheduleNotification extends Command
{
    protected $signature = 'app:one-hour-student-schedule-notification';
    protected $description = 'Kirim notifikasi jika waktu tersisa 1 jam untuk jadwal pelajaran siswa';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $targetTime = Carbon::now()->addHour();
        // Ditambahkan lagi nanti ketika Domain schedule sudah dibuat
    }
}
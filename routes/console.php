<?php

use App\Models\Notification;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('model:prune', [
    '--model' => [Notification::class],
])->daily();

// Artisan command untuk membuat file baru di folder Domain

Artisan::command('domain:port {domainName} {name}', function (string $domainName, string $name){
    $directory = app_path("Domains/{$domainName}/Ports");
    $filePath = "{$directory}/{$name}.php";

    if (!File::exists($directory)) {
        File::makeDirectory($directory, 0755, true);
    }

    if (File::exists($filePath)) {
        $this->error("Port {$name} sudah ada di domain {$domainName}!");
        return;
    }

    $template = <<<PHP
<?php

namespace App\Domains\\{$domainName}\Ports;

interface {$name}
{
}
PHP;

    File::put($filePath, $template);

    $this->info("Berhasil membuat Port: {$filePath}");
    }
)->purpose('Membuat file port baru di folder Domain');


Artisan::command('domain:repository {domainName} {name}', function (string $domainName, string $name){
    $directory = app_path("Domains/{$domainName}/Infrastructure/Repository");
    $filePath = "{$directory}/{$name}.php";

    if (!File::exists($directory)) {
        File::makeDirectory($directory, 0755, true);
    }

    if (File::exists($filePath)) {
        $this->error("Repository {$name} sudah ada di domain {$domainName}!");
        return;
    }

    $template = <<<PHP
<?php

namespace App\Domains\\{$domainName}\Infrastructure\Repository;

class {$name} implements 
{
}
PHP;

    File::put($filePath, $template);

    $this->info("Berhasil membuat repository: {$filePath}");
    }
)->purpose('Membuat file repository baru di folder Domain');

Artisan::command('domain:action {domainName} {name}', function (string $domainName, string $name){
    $directory = app_path("Domains/{$domainName}/Actions");
    $filePath = "{$directory}/{$name}.php";

    if (!File::exists($directory)) {
        File::makeDirectory($directory, 0755, true);
    }

    if (File::exists($filePath)) {
        $this->error("Action {$name} sudah ada di domain {$domainName}!");
        return;
    }

    $template = <<<PHP
<?php

namespace App\Domains\\{$domainName}\Actions;

class {$name}
{
    public function __construct() {}

    public function execute()
    {
        // return ;
    }
}
PHP;

    File::put($filePath, $template);

    $this->info("Berhasil membuat action: {$filePath}");
    }
)->purpose('Membuat file action baru di folder Domain');

Artisan::command('domain:entity {domainName} {name}', function (string $domainName, string $name){
    $directory = app_path("Domains/{$domainName}/Entities");
    $filePath = "{$directory}/{$name}.php";

    if (!File::exists($directory)) {
        File::makeDirectory($directory, 0755, true);
    }

    if (File::exists($filePath)) {
        $this->error("Entity {$name} sudah ada di domain {$domainName}!");
        return;
    }

    $template = <<<PHP
<?php

namespace App\Domains\\{$domainName}\Entities;

class {$name} {
    public function __construct(
    ) {}
}

PHP;

    File::put($filePath, $template);

    $this->info("Berhasil membuat entity: {$filePath}");
    }
)->purpose('Membuat file entity baru di folder Domain');

Artisan::command('domain:controller {domainName} {name}', function (string $domainName, string $name){
    $directory = app_path("Domains/{$domainName}/Infrastructure/Delivery/Http/Controllers");
    $filePath = "{$directory}/{$name}.php";

    if (!File::exists($directory)) {
        File::makeDirectory($directory, 0755, true);
    }

    if (File::exists($filePath)) {
        $this->error("Controller {$name} sudah ada di domain {$domainName}!");
        return;
    }

    $template = <<<PHP
<?php

namespace App\Domains\\{$domainName}\Infrastructure\Delivery\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class {$name} extends Controller
{
    
}

PHP;

    File::put($filePath, $template);

    $this->info("Berhasil membuat controller: {$filePath}");
    }
)->purpose('Membuat file controller baru di folder Domain');

Artisan::command('domain:notification {domainName} {name}', function (string $domainName, string $name){
    $directory = app_path("Domains/{$domainName}/Notifications");
    $filePath = "{$directory}/{$name}.php";

    if (!File::exists($directory)) {
        File::makeDirectory($directory, 0755, true);
    }

    if (File::exists($filePath)) {
        $this->error("Notifikasi {$name} sudah ada di domain {$domainName}!");
        return;
    }

    $template = <<<PHP
<?php

namespace App\Domains\\{$domainName}\Notifications;

use App\Domains\Notification\Infrastructure\External\Firebase\FcmChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class {$name} extends Notification
{
    use Queueable;

    public function __construct(protected array \$details) {}

    public function via(\$notifiable): array
    {
        return ['database', FcmChannel::class];
    }

    public function toDatabase(\$notifiable): array
    {
        return [
            'title' => \$this->details['title'],
            'body' => \$this->details['body'],
            'data' => \$this->details['data'] ?? [],
        ];
    }
    
    public function toFcm(\$notifiable): array
    {
        return [
            'title' => \$this->details['title'],
            'body' => \$this->details['body'],
            'data' => \$this->details['data'] ?? [],
            ]
        ];
    }
}

PHP;

    File::put($filePath, $template);

    $this->info("Berhasil membuat notifikasi: {$filePath}");
    }
)->purpose('Membuat file notifikasi baru di folder Domain');






Artisan::command('domain:command {domainName} {name}', function (string $domainName, string $name){
    $directory = app_path("Domains/{$domainName}/Infrastructure/Delivery/Console/Commands");
    $filePath = "{$directory}/{$name}.php";

    if (!File::exists($directory)) {
        File::makeDirectory($directory, 0755, true);
    }

    if (File::exists($filePath)) {
        $this->error("Notifikasi {$name} sudah ada di domain {$domainName}!");
        return;
    }

    $template = <<<PHP
<?php

namespace App\Domains\\{$domainName}\Infrastructure\Delivery\Console\Commands;

use Illuminate\Console\Command;

class {$name} extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected \$signature = 'app:one-hour-student-schedule-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected \$description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
    }
}
PHP;

    File::put($filePath, $template);

    $this->info("Berhasil membuat notifikasi: {$filePath}");
    }
)->purpose('Membuat file notifikasi baru di folder Domain');

Artisan::command('domain:service {domainName} {name}', function (string $domainName, string $name){
    $directory = app_path("Domains/{$domainName}/Infrastructure/Services");
    $filePath = "{$directory}/{$name}.php";

    if (!File::exists($directory)) {
        File::makeDirectory($directory, 0755, true);
    }

    if (File::exists($filePath)) {
        $this->error("Service {$name} sudah ada di domain {$domainName}!");
        return;
    }

    $template = <<<PHP
<?php

namespace App\Domains\\{$domainName}\Infrastructure\Services;

class {$name} implements 
{
}
PHP;

    File::put($filePath, $template);

    $this->info("Berhasil membuat service: {$filePath}");
    }
)->purpose('Membuat file service baru di folder Domain');

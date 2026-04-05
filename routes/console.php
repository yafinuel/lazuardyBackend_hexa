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
    /**
     * Define your contract methods here
     */
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
    /**
     * Create a new class instance.
     */
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

class {$name}
    /**
     * Create a new class instance.
     */
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

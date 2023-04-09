<?php

namespace Fomvasss\Lte3\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lte3:install';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the Lte resources';
    /**
     * Execute the console command.
     *
     * @return mixed
     */

    protected string $slug;

    public function handle()
    {
        $slug = $this->ask('Dashboard slug:', config('lte3.dashboard_slug'));
        $this->slug = trim(Str::snake($slug), '/');

        if (!$this->publishAdminlte()) {
            return false;
        }

        if (!$this->publishLte3()) {
            return false;
        }

        if (!File::exists(public_path('storage'))) {
            $this->callSilent('storage:link');
        }

        $this->info('Lte3 installed successfully!');
    }

    public function publishAdminlte()
    {
        if (!File::exists(base_path('/vendor/almasaeed2010'))) {
            $this->warn("Packege in not installed. Please install the almasaeed2010/adminlte");
            return false;
        }

        $adminlteDir = public_path('vendor/adminlte');
        if (File::exists($adminlteDir)) {
            $this->warn("Derectory [{$adminlteDir}] already exists!");
        } else {
            File::makeDirectory($adminlteDir, 0755, true);
        }

        $t = $this->choice('Copy files or make symbolic links?', ['Copy', 'Symbilic'], 0);
        if ($t === 'Copy') {
            File::copyDirectory(base_path('vendor/almasaeed2010/adminlte/dist'), public_path('vendor/adminlte/dist'));
            File::copyDirectory(base_path('vendor/almasaeed2010/adminlte/plugins'), public_path('vendor/adminlte/plugins'));
        } else {
            File::link(base_path('vendor/almasaeed2010/adminlte/dist'), public_path('vendor/adminlte/dist'));
            File::link(base_path('vendor/almasaeed2010/adminlte/plugins'), public_path('vendor/adminlte/plugins'));
        }

        return true;
    }

    public function publishLte3()
    {
        $viewsFullPath = base_path('resources/views');
        $viewsPath = str_replace(base_path(), '', $viewsFullPath);

        $lte3Path = $viewsFullPath . '/' . $this->slug;

        if (File::exists($lte3Path)) {
            $this->warn("Already exists path [{$lte3Path}]. Enter other name.");
            return false;
        }

        File::copyDirectory(__DIR__.'/../../resources/views/auth', $lte3Path . '/auth');
        File::copyDirectory(__DIR__.'/../../resources/views/examples', $lte3Path . '/examples');
        File::copyDirectory(__DIR__.'/../../resources/views/layouts', $lte3Path . '/layouts');
        File::copyDirectory(__DIR__.'/../../resources/views/parts', $lte3Path . '/parts');
        $this->callSilent('vendor:publish', ['--tag' => 'lte3-assets']);

        $res = [];
        foreach (File::allFiles($lte3Path) as $file) {
            $pathFile = $file->getPathname();
            $res[] = $pathFile;
            $str = file_get_contents($pathFile);
            $str = str_replace("'lte3::", "'{$this->slug}.", $str);
            file_put_contents($pathFile, $str);
        }

        $lte3Routes = base_path("routes/{$this->slug}.php");
        $homeRoute = "Route::view('{$this->slug}', '{$this->slug}.examples.home');";
        File::put($lte3Routes, "<?php\n\n$homeRoute");

        $webRoutes = base_path('routes/web.php');
        File::append($webRoutes, "require __DIR__ . '/{$this->slug}.php';\n");

        $this->info("Views: [{$viewsPath}]");
        $this->info("Visit: " . url($this->slug));

        return true;
    }
}

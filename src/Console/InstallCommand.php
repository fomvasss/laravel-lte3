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
    public function handle()
    {
        $this->callSilent('vendor:publish', ['--tag' => 'adminlte-assets']);
        $this->comment('AdminLTE assets see in: [' . public_path('vendor/adminlte') . ']');
        $this->callSilent('vendor:publish', ['--tag' => 'lte3-config']);
        $this->callSilent('vendor:publish', ['--tag' => 'lte3-assets']);
        $this->publishesLte3Viewss();

        // $this->comment('Publishing LFM Config & Assets...');
        // $this->callSilent('vendor:publish', ['--tag' => 'lfm_config']);
        // $this->callSilent('vendor:publish', ['--tag' => 'lfm_public']);

        if (!File::exists(public_path('storage'))) {
            $this->callSilent('storage:link');
        }

        $this->info('Lte3 installed successfully!');
    }


    public function publishesLte3Viewss()
    {
        $viewsFullPath = config('view.paths')[0] ?? base_path('resources/views');
        $viewsPath = str_replace(base_path(), '', $viewsFullPath);

        //$this->comment("Current views path: [{$viewsPath}]");
        $slug = $this->ask('Enter dashboard slug:', 'admin');
        $dirName = Str::camel($slug);
        $dirName = trim($dirName, '/');
        $r = $viewsFullPath . '/' . $dirName;

        if (File::exists($r)) {
            $this->error("Already exists path [{$r}]. Copied not possible.");
            return false;
        }

        File::copyDirectory(__DIR__.'/../../resources/views/auth', $r . '/auth');
        File::copyDirectory(__DIR__.'/../../resources/views/examples', $r . '/examples');
        File::copyDirectory(__DIR__.'/../../resources/views/layouts', $r . '/layouts');
        File::copyDirectory(__DIR__.'/../../resources/views/parts', $r . '/parts');

        $res = [];
        foreach (File::allFiles($r) as $file) {
            $path = $file->getPathname();
            $res[] = $path;
            $str = file_get_contents($path);
            $str = str_replace("'lte3::", "'{$dirName}.", $str);
            file_put_contents($path, $str);
        }

        $routes = base_path('routes/web.php');
        File::append($routes, "Route::view('{$dirName}', '{$dirName}.examples.home');\n");

        $this->comment("Views see in [{$r}]");
        $this->info("Visit:" . url($dirName));
    }
}

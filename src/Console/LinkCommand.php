<?php

namespace Fomvasss\Lte3\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class LinkCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lte3:link';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make link to Lte assets';
    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function handle()
    {
        if (!File::exists(public_path('vendor/adminlte'))) {
            if (File::exists(base_path('/vendor/almasaeed2010'))) {
                File::makeDirectory(public_path('vendor/adminlte'), 0755, true);
                File::link(base_path('vendor/almasaeed2010/adminlte/dist'), public_path('vendor/adminlte/dist'));
                File::link(base_path('vendor/almasaeed2010/adminlte/plugins'), public_path('vendor/adminlte/plugins'));
            } else {
                $this->warn("Packege in not installed. Please run: composer require almasaeed2010/adminlte");
            }
        }

        if (!File::exists(public_path('vendor/lte3'))) {
            File::link(__DIR__.'/../../public', public_path('vendor/lte3'));
        }

        if (!File::exists(public_path('storage'))) {
            $this->callSilent('storage:link');
        }

        $this->info('Lte3 link maked!');
    }
}

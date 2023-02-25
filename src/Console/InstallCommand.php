<?php

namespace Fomvasss\Lte3\Console;

use Illuminate\Console\Command;

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
        $this->comment('Publishing Lte Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'lte3-config']);

        $this->comment('Publishing AdminLTE Assets...');
        $this->callSilent('vendor:publish', ['--tag' => 'adminlte-assets']);

        $this->comment('Publishing Lte Assets...');
        $this->callSilent('vendor:publish', ['--tag' => 'lte3-assets']);

        $this->comment('Publishing Lte Lang...');
        $this->callSilent('vendor:publish', ['--tag' => 'lte3-lang']);

        $this->comment('Publishing Lte Views Content...');
        $this->callSilent('vendor:publish', ['--tag' => 'lte3-view-profile']);
        $this->callSilent('vendor:publish', ['--tag' => 'lte3-view-auth']);
        $this->callSilent('vendor:publish', ['--tag' => 'lte3-view-examples']);
        $this->callSilent('vendor:publish', ['--tag' => 'lte3-view-layouts']);

        // $this->comment('Publishing LFM Config & Assets...');
        // $this->callSilent('vendor:publish', ['--tag' => 'lfm_config']);
        // $this->callSilent('vendor:publish', ['--tag' => 'lfm_public']);

        $this->comment('Creating storage link...');
        $this->callSilent('storage:link');

        $this->info('Lte scaffolding installed successfully. Visit ' . route('lte.home'));
    }
}

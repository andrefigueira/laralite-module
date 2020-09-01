<?php

namespace Modules\Laralite\Console;

use Illuminate\Console\Command;
use DB;

class RefreshComponents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laralite:refresh-components';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refreshes components database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Refreshing components...');

        if (strtolower($this->ask('Are you sure you want to do this? Y/N')) !== 'y') {
            $this->warn('Aborting - Will not refresh components');

            return;
        }

        $this->warn('- Truncating components table');

        DB::table('components')->truncate();

        $this->warn('- Inserting components');

        foreach (config('laralite.components') as $component) {
            $this->warn('-- Inserting component: ' . $component['name']);

            if (isset($component['properties'])) {
                $component['properties'] = json_encode($component['properties']);
            }

            DB::table('components')->insert($component);
        }

        $this->info('Refresh complete');
    }
}

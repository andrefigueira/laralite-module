<?php

namespace Modules\Laralite\Console;

use Illuminate\Console\Command;
use DB;
use Modules\Laralite\Models\Component;
use Modules\Laralite\Models\Template;

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



        $moduleStatusFile = dirname(__DIR__, 3) . '/modules_statuses.json';
        $moduleStatuses = json_decode(file_get_contents($moduleStatusFile));

        foreach ($moduleStatuses as $name => $status) {
            $configKey = strtolower($name);
            $moduleConfig = config($configKey);

            if (isset($moduleConfig['components'])) {
                $this->info('Components defined for (' . $name . ') module');

                foreach ($moduleConfig['components'] as $component) {
                    $this->warn('-- Inserting component: ' . $component['name']);

                    Component::create($component);
                }
            } else {
                $this->warn('No components defined for (' . $name . ') module, skipping...');
            }
        }

        $this->info('Refresh complete');
    }
}

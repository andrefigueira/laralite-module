<?php

namespace Modules\Laralite\Console;

use Illuminate\Console\Command;
use DB;
use Modules\Laralite\Models\Template;

class RefreshTemplates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laralite:refresh-templates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refreshes templates';

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
        $this->info('Refreshing module templates');

        $moduleStatusFile = dirname(__DIR__, 3) . '/modules_statuses.json';
        $moduleStatuses = json_decode(file_get_contents($moduleStatusFile));

        foreach ($moduleStatuses as $name => $status) {
            $configKey = strtolower($name);
            $moduleConfig = config($configKey);

            if (isset($moduleConfig['templates'])) {
                $this->info('Templates defined for (' . $name . ') module');

                foreach ($moduleConfig['templates'] as $template) {
                    $this->info('Creating new template (' . $template['name'] . ')');

                    Template::firstOrCreate(['name' => $template['name']],[
                        'module_name' => $name,
                        'name' => $template['name'],
                        'description' => $template['description'],
                        'sections' => $template['sections'],
                    ]);
                }
            } else {
                $this->warn('No templates defined for (' . $name . ') module, skipping...');
            }
        }

        $this->info('Finished refreshing module templates');
    }
}

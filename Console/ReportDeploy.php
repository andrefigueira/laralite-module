<?php

namespace Modules\Laralite\Console;

use GuzzleHttp\Client;
use Illuminate\Console\Command;

class ReportDeploy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy:report {--status=} {--environment=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Report deploy to Rollbar';

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
        $client = new Client();

        try {
            $this->info('Attempting to notify Rollbar of deploy...');

            if (!in_array($this->option('status'), [
                'started',
                'succeeded',
                'failed',
                'timed_out',
            ], true)) {
                throw new \Exception('Status ' . $this->option('status') . ' does not exist!');
            }

            $url = 'https://api.rollbar.com/api/1/deploy';
            $options = [
                'body' => json_encode([
                    'access_token' => env('ROLLBAR_TOKEN'),
                    'environment' => $this->option('environment'),
                    'revision' => shell_exec('git log -n 1 --pretty=format:"%H"'),
                    'local_username' => shell_exec('git log -1 --pretty=format:"%an"'),
                    'status' => $this->option('status'),
                ]),
            ];

            $result = $client->post($url, $options);

            if ($result->getStatusCode() !== 200) {
                throw new \Exception('Bad response from Rollbar');
            }

            $this->info('Rollbar notified of deploy successfully');
        } catch (\Throwable $exception) {
            $this->error('Something went wrong when trying to communicate with Rollbar');
            $this->error('Exception: ' . $exception->getMessage());
        }
    }
}

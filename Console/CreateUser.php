<?php

namespace Modules\Laralite\Console;

use Illuminate\Console\Command;
use DB;
use Hash;
use League\CommonMark\Block\Element\ThematicBreak;
use Modules\Laralite\Models\User;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laralite:create-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Allows the creation of a user';

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
        $name = $this->ask('Please enter a name');
        $email = $this->ask('Please enter a valid email address');
        $password = Hash::make($this->ask('Please enter a password'));

        $user = User::where('email', '=', $email);

        if ($user->exists()) {
            $this->error('Sorry but a user with that email already exists!');

            return;
        }

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        $this->info('User created successfully');
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Faker\Factory as Faker;

class nameFaker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'runNameFaker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run name faker for all users';

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
     
        $users = \App\User::all();
        foreach ($users as $user){
            $faker = Faker::create();
            $user->firstname = $faker->firstNameFemale;
            $user->middlename = $faker->lastName();
            $user->lastname = $faker->lastName();
            $user->save();
            
            $this->info($user->idno.': '.$user->firstname.' '.$user->lastname);
        }
    }
}

<?php

namespace App\Console\Commands;

use App\Console\DatabaseCommand;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CreateDatabaseUser extends DatabaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:user
                            {database}
                            {connection? : connection to use for this action}
                            {username?}
                            {password?}
                            {--G|grant}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new user on the default / specified connection and grants privileges to the given database';

    /**
     * The Faker object to generate a random password
     *
     * @var Faker
     */
    protected $faker;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->faker = Faker::create();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->argument('connection') ? $connectionName = $this->argument('connection') : $connectionName = config('database.default');

        $connection = DB::connection($connectionName);
        $config = config('database.connections.'.$connectionName);
        $db = $this->argument('database');
        $fullDBname = $db.'@'.$config['host'].':'.$config['port'];

        $this->argument('username') ? $user = $this->argument('username') : $user = $db.'_U' ;
        $this->argument('password') ? $password = $this->argument('password') : $password = $this->faker->word;
        $this->line("Creating user `$user` and adding schema privileges...");
        $login_details = "Login with $user:$password";

        if($config['driver'] === 'mysql') {
            if($this->doesUserExist($user, $connection)) {
                $connection->statement("SET PASSWORD FOR '$user'@'%' = PASSWORD('$password')");  //update if user exists
            } else {
                $connection->statement("CREATE USER '$user'@'%' IDENTIFIED BY '$password'");
            }
            if($this->option('grant')) {
                if( $this->doesDBexist($db, $connection)) {
                    $connection->statement("GRANT ALL PRIVILEGES ON $db.* TO '$user'@'%' WITH GRANT OPTION");
                } else {
                    $this->error("The $db database does not exist! run db:create and rerun this command to grant the user access");
                }
            }

            $this->info('Success!');
            $this->comment("$login_details over at $fullDBname");
        } else {
            $this->warn('Driver '.$config['driver'].' is not yet supported!');
        }

    }
}

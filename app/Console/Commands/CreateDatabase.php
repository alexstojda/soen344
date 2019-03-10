<?php

namespace App\Console\Commands;

use App\Console\DatabaseCommand;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CreateDatabase extends DatabaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:create 
                            {name : Name of the new database to create} 
                            {connection? : connection to use for this action}
                            {username?}
                            {password?}
                            {--U|user : flag to run the db:user command and generate a new user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new database on the default / specified connection';

    /**
     * The database configuration array
     *
     * @var mixed
     */
    protected $config;

    /**
     * The fully qualified database connection details
     *
     * @var string
     */
    protected $fullDBname;

    /**
     * The Connection object to execute raw db statements over
     *
     * @var mixed
     */
    protected $connection;

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
        if($this->argument('connection')) {
            $connectionName = $this->argument('connection');
        } else {
            $connectionName = config('database.default');
        }

        $connection = DB::connection($connectionName);
        $this->config = config('database.connections.'.$connectionName);
        $db = $this->argument('name');
        $this->fullDBname = $db.'@'.$this->config['host'].':'.$this->config['port'];

        if(!$this->doesDBexist($db, $connection)) {
            if($this->config['driver'] === 'mysql') {
                $this->line("Creating database $this->fullDBname via `$connectionName` connection...");
                $char = $this->config['charset'];
                $col = $this->config['collation'];
                $connection->statement("CREATE DATABASE IF NOT EXISTS $db CHARACTER SET $char COLLATE $col");
                if($this->doesDBexist($db, $connection)) {
                    $this->info('Success!');
                    if($this->option('user')) {
                        $this->call('db:user',[
                            'database' => $db,
                            'connection' => $connectionName,
                            'username' => $this->argument('username'),
                            'password' => $this->argument('password'),
                            '--grant' => true
                        ]);
                    }
                } else {
                    $this->error('Something went wrong!!! The database doesnt exist, check that your db credentials have the right permissions!');
                }
            } else {
                $this->error('Currently only mysql driver is supported.');
            }
        } else {
            $this->error("The $db database name already exists on the $this->fullDBname over the $connectionName connection!");
        }
    }
}

<?php

namespace App\Console\Commands;

use App\Console\DatabaseCommand;
use Illuminate\Support\Facades\DB;

class DeleteDatabase extends DatabaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:delete 
                            {name : Name of the database to delete} 
                            {username? : username associated to the database to delete}
                            {connection? : connection to use for this action}
                            {--U|user}
                            {--S|skip-check}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes a database from the default / specified connection';

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
        $user = $this->argument('username');
        $this->fullDBname = $db.'@'.$this->config['host'].':'.$this->config['port'];

        if($this->config['driver'] === 'mysql' ) {
            $candidates = $this->allDatabasesLike($db, $connection);
            if ($candidates->count() > 0) {
                $candidates->each(function ($db) use ($connection) {
                    if($this->option('skip-check') || $this->confirm("Are you sure you want to delete the $db db?")) {
                        $connection->statement("DROP DATABASE $db");
                        if(!$this->doesDBexist($db, $connection)) {
                            $this->info('Successfully removed '.$db.' database!');
                        } else {
                            $this->error('Something went wrong!!! The database still exists, check that your db credentials have the right permissions!');
                        }
                    }
                });
            } else {
                $this->warn('Cannot find a database matching \''.$db.'\', check that your db credentials have the right permissions or maybe it is already deleted.');
            }

            if ($this->option('user')) {
                $candidates = $this->allUsersLike($user, $connection);
                if ($candidates->count() > 0) {
                    $candidates->each(function ($user) use ($connection) {
                        if($this->option('skip-check') || $this->confirm("Are you sure you want to delete the $user user?")) {
                            $connection->statement("DROP USER '$user'");
                            if(!$this->doesUserExist($user, $connection)) {
                                $this->info('Successfully removed '.$user.' user!');
                            } else {
                                $this->error('Something went wrong!!! The user still exists, check that your db credentials have the right permissions!');
                            }
                        }
                    });
                } else {
                    $this->warn('Cannot find a user matching \''.$user.'\', check that your db credentials have the right permissions or maybe it is already deleted.');
                }
            }
        } else {
            $this->error('Currently only mysql driver is supported.');
        }
    }
}

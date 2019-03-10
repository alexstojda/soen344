<?php

namespace App\Console;

use Illuminate\Console\Command;

class DatabaseCommand extends Command
{
    protected function doesDBexist($name, $connection) {
        return $connection->table('INFORMATION_SCHEMA.SCHEMATA')->where('SCHEMA_NAME', $name)->exists();
    }

    protected function doesUserExist($name, $connection) {
        return $connection->table('mysql.user')->where('user', $name)->exists();
    }

    protected function allDatabasesLike($name, $connection) {
        return $connection->table('INFORMATION_SCHEMA.SCHEMATA')->whereNotIn('SCHEMA_NAME', config('cng.database.protected.schemas'))
            ->where('SCHEMA_NAME', 'like', $name)->pluck('SCHEMA_NAME');
    }

    protected function allUsersLike($name, $connection) {
        return $connection->table('mysql.user')->whereNotIn('user', config('cng.database.protected.users'))
            ->where('user', 'like', $name)->pluck('user');
    }
}

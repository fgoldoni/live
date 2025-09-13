<?php

namespace Core\Traits\Database;

use Illuminate\Support\Facades\DB;

trait TruncateTable
{
    protected function truncate(string $table)
    {
        return match (DB::getDriverName()) {
            'mysql'  => DB::table($table)->truncate(),
            'pgsql'  => DB::statement('TRUNCATE TABLE ' . $table . ' RESTART IDENTITY CASCADE'),
            'sqlite' => DB::statement('DELETE FROM ' . $table),
            default  => false,
        };
    }

    protected function truncateMultiple(array $tables)
    {
        foreach ($tables as $table) {
            $this->truncate($table);
        }
    }
}

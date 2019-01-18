<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupDatabase extends Command
{
    protected $signature = 'db:backup';

    protected $description = 'Backup the database';

    protected $process;

    public function __construct()
    {
        parent::__construct();

        $this->process = new Process(sprintf(
            'mysqldump -h %s -u%s -p%s %s' .      // make db dump
            '| gzip > %s ' .                            // compress db dump
            '&& touch %s ' .                            // update .gitignore date
            '&& find %s -mtime +6 -type f -delete',     // delete files older than 1 week
            config('database.connections.mysql.host'),
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            storage_path('backup/back_' . date('d-m-y_H-i-s') . '.sql.gz'),
            storage_path('backup/.gitignore'),
            storage_path('backup/')
        ));
    }

    public function handle()
    {
        try {
            $this->process->mustRun();

            $this->info('The backup has been proceed successfully.');
        } catch (ProcessFailedException $exception) {
            $this->error('The backup process failed - ' . $exception->getMessage());
        }
    }
}

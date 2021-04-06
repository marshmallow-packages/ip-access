<?php

namespace Marshmallow\IpAccess\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class UninstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ip-access:uninstall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Uninstall the ip-access implementation once your application will be running in production';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->deleteTheConfigFile();
        $this->deleteTheNovaResource();
        $this->deleteTheDatabaseTable();
        $this->deleteTheMigrationRecord();
        $this->deleteTheComposerPackage();

        $this->info('💣 The ip-access packages has successfully been deleted. Please commit your changes.');
    }

    protected function deleteTheConfigFile()
    {
        $config_file = base_path('config/ip-access.php');
        if (file_exists($config_file)) {
            unlink($config_file);
        }
    }

    protected function deleteTheNovaResource()
    {
        $resource_path = app_path('Nova/IpAccess.php');
        if (file_exists($resource_path)) {
            unlink($resource_path);
        }
    }

    protected function deleteTheDatabaseTable()
    {
        if (Schema::hasTable('ip_accesses')) {
            Schema::drop('ip_accesses');
        }
    }

    protected function deleteTheMigrationRecord()
    {
        DB::delete('DELETE FROM migrations WHERE migration = "2021_01_23_130228_create_ip_accesses_table"');
    }

    protected function deleteTheComposerPackage()
    {
        $path = base_path();
        exec("cd {$path} && composer remove marshmallow/ip-access");
    }
}

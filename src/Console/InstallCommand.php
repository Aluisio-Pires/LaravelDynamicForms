<?php

namespace App\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dynamic-forms:install {--a : Indicate that all components should be installed}
                                              {--models : Indicate that models should be installed}
                                              {--traits : Indicates that traits should be installed}
                                              {--migrations : Indicates that migrations should be installed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Dynamic Forms components.';

    /**
     * Execute the console command.
     *
     * @return int|null
     */
    public function handle()
    {
        $all = false;
        $models = $this->option('models');
        $traits = $this->option('traits');
        $migrations = $this->option('migrations');

        if(!($models || $traits || $migrations)) {
            $all = true;
        }

        if ($all || $models) {
            $this->copyFromStubs('/app/Models/Field.php');
            $this->copyFromStubs('/app/Models/Form.php');
            $this->copyFromStubs('/app/Models/Validation.php');
        }

        if ($all || $traits) {
            $this->copyFromStubs('/app/Traits/HasForms.php');
        }

        if ($all || $migrations) {
            $this->copyFromStubs('/app/database/migrations/2023_00_00_000000_create_forms_table.php');
            $this->copyFromStubs('/app/database/migrations/2023_00_00_000001_create_fields_table.php');
            $this->copyFromStubs('/app/database/migrations/2023_00_00_000002_create_form_field_table.php');
            $this->copyFromStubs('/app/database/migrations/2023_00_00_000003_create_validations_table.php');
            $this->copyFromStubs('/app/database/migrations/2023_00_00_000004_create_form_field_form_field_validation_table.php');
            $this->copyFromStubs('/app/database/migrations/2023_00_00_000005_create_fildables_table.php');
        }

        return 0;
    }

    /**
     * Returns the path to the correct test stubs.
     *
     * @return string
     */
    protected function getTestStubsPath()
    {
        return __DIR__.'/../../stubs';
    }

    /**
     * Copy stubs files to laravel base path
     * @param $path
     */
    protected function copyFromStubs($path){
        $stubs = $this->getTestStubsPath();
        copy($stubs.$path, base_path($path));
    }
}

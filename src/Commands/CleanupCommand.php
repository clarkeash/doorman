<?php

namespace Clarkeash\Doorman\Commands;

use Illuminate\Console\Command;

class CleanupCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'doorman:cleanup';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove expired invites from the database.';
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $modelClass = config('doorman.invite_model');
        $useless = $modelClass::useless()->count();
        $modelClass::useless()->delete();
        $this->info('Successfully deleted ' . $useless . ' expired invites from the database.');
    }
}

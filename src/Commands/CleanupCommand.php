<?php

namespace Clarkeash\Doorman\Commands;

use Illuminate\Console\Command;
use Clarkeash\Doorman\Models\Invite;

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
        $inviteModel = config('doorman.model');

        $useless = $inviteModel::useless()->count();
        $inviteModel::useless()->delete();

        $this->info('Successfully deleted ' . $useless . ' expired invites from the database.');
    }
}

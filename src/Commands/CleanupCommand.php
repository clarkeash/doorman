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
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $useless = Invite::useless()->count();
        Invite::useless()->delete();
        $this->info('Successfully deleted '.$useless.' expired invites from the database.');
    }
}

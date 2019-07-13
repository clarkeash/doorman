<?php

namespace Clarkeash\Doorman\Commands;

use Clarkeash\Doorman\Models\BaseInvite;
use Illuminate\Console\Command;

class CleanupCommand extends Command
{
    /**
     * @var BaseInvite
     */
    protected $invite;

    public function __construct(BaseInvite $invite)
    {
        parent::__construct();
        $this->invite = $invite;
    }

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
        $useless = $this->invite->useless()->count();
        $this->invite->useless()->delete();
        $this->info('Successfully deleted ' . $useless . ' expired invites from the database.');
    }
}

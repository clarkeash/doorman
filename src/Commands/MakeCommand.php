<?php

namespace Clarkeash\Doorman\Commands;

use Clarkeash\Doorman\Facades\Doorman;
use Clarkeash\Doorman\Generator;
use Clarkeash\Doorman\Models\BaseInvite;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class MakeCommand extends Command
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
    protected $signature = 'doorman:make {--email=} {--uses=1} {--unlimited} {--expiry=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new invite code.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            /** @var Generator $generator */
            $generator = Doorman::generate();

            if ($this->option('email')) {
                $generator->for($this->option('email'));
            }

            if ($this->option('uses')) {
                $generator->uses($this->option('uses'));
            }

            if ($this->option('unlimited')) {
                $generator->unlimited();
            }

            if ($this->option('expiry')) {
                $generator->expiresOn(Carbon::parse($this->option('expiry'))->endOfDay());
            }

            $invite = $generator->once();

            $this->info('Successfully created invite code: ' . $invite->code);
        } catch (\Exception $e) {
            $this->error('Failed to create invite code: ' . $e->getMessage());
        }
    }
}

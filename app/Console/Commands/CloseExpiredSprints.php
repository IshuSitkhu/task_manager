<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sprint;
class CloseExpiredSprints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:close-expired-sprints';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sprints = Sprint::where('status', 'active')
            ->whereDate('end_date', '<', today())
            ->get();

        foreach ($sprints as $sprint) {
            $sprint->status = 'closed';
            $sprint->save();
        }

        $this->info("Closed {$sprints->count()} expired sprint(s).");
    }
}

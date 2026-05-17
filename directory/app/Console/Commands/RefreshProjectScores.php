<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use Illuminate\Support\Facades\Log;

class RefreshProjectScores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'projects:refresh-scores';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh all project scores based on current data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $projects = Project::all();
        $this->info("Refreshing scores for {$projects->count()} projects...");

        foreach ($projects as $project) {
            $project->calculateScores();
            $this->line("   - {$project->name} refreshed.");
        }

        $this->info("Score refresh completed.");
        Log::info("All project scores refreshed by cron job.");
    }
}

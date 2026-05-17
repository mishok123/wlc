<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MonitorProjectHealth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:health';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check online status of all listed projects';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $projects = Project::whereNotNull('website_url')->get();
        $this->info("Starting health check for {$projects->count()} projects...");

        foreach ($projects as $project) {
            $this->checkProject($project);
        }

        $this->info("Health check completed.");
    }

    private function checkProject($project)
    {
        $urls = $project->website_urls;
        $status = 'offline';
        $results = [];
        $urlStatuses = [];

        foreach ($urls as $url) {
            $checkUrl = $url;
            if (!preg_match('~^(?:f|ht)tps?://~i', $url)) {
                $checkUrl = 'https://' . $url;
            }
            
            $urlStatus = 'offline';
            try {
                $response = Http::timeout(10)->withoutVerifying()->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
                ])->get($checkUrl);
                
                $statusCode = $response->status();
                // Treat Success (2xx), Redirects (3xx), and most Client Errors (4xx) as Online
                // 404 (Not Found) and 410 (Gone) are treated as Offline.
                // 5xx (Server Errors) are treated as Offline.
                if (($statusCode >= 200 && $statusCode < 400) || ($statusCode >= 400 && $statusCode < 500 && !in_array($statusCode, [404, 410]))) {
                    $status = 'online';
                    $urlStatus = 'online';
                    $results[] = "✅ $checkUrl: Online ($statusCode)";
                } else {
                    $results[] = "❌ $checkUrl: Offline ($statusCode)";
                }
            } catch (\Exception $e) {
                // If it fails with https, maybe try http fallback just in case
                if (str_starts_with($checkUrl, 'https://')) {
                    try {
                        $fallbackUrl = 'http://' . ltrim($url, 'https://');
                        $response = Http::timeout(10)->withoutVerifying()->withHeaders([
                            'User-Agent' => 'Mozilla/5.0'
                        ])->get($fallbackUrl);
                        if ($response->successful() && $response->status() !== 403) {
                            $status = 'online';
                            $urlStatus = 'online';
                            $results[] = "✅ $fallbackUrl: Online";
                        }
                    } catch (\Exception $ex) {
                        // Ignore inner fallback failures
                    }
                }
                
                if ($urlStatus === 'offline') {
                    $results[] = "❌ $checkUrl: Error ({$e->getMessage()})";
                }
            }
            
            $urlStatuses[$url] = $urlStatus;
        }

        foreach ($results as $res) {
            $this->line("   " . $res);
        }

        if ($status === 'online') {
            $this->line("✅ {$project->name}: Online");
        } else {
            $this->error("❌ {$project->name}: All URLs offline");
        }

        // update project with aggregate and individual statuses
        $project->update([
            'online_status' => $status,
            'url_statuses' => $urlStatuses
        ]);
        
        Log::info("Project {$project->name} health checked. Status: {$status}");
    }
}

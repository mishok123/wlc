<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\ProjectForm;
use App\Models\Project;
use App\Models\ProjectReport;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;
use Livewire\WithPagination;

class Projects extends Component
{
    use WithPagination;

    public $search = '';
    public $showReportsModal = false;
    public $selectedProjectReports = [];
    public $selectedProjectName = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function recalculateAllScores()
    {
        $projects = Project::all();
        foreach ($projects as $project) {
            $project->calculateScores();
        }
        session()->flash('message', 'All project scores have been recalculated against the new global maximums!');
    }

    public function runHealthCheck()
    {
        Artisan::call('monitor:health');
        session()->flash('message', 'Health check completed successfully!');
    }

    public function viewReports($projectId)
    {
        $project = Project::find($projectId);
        if ($project) {
            $this->selectedProjectName = $project->name;
            $this->selectedProjectReports = ProjectReport::where('project_id', $projectId)
                ->with('user')
                ->latest()
                ->get()
                ->toArray();
            $this->showReportsModal = true;
        }
    }

    public function closeReportsModal()
    {
        $this->showReportsModal = false;
        $this->selectedProjectReports = [];
        $this->selectedProjectName = '';
    }

    public function render()
    {
        $projects = Project::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->with(['category', 'user'])
            ->withCount([
                'requests as approve_requests_count' => function ($query) {
                    $query->where('request_type', 'approve');
                },
                'requests as verify_requests_count' => function ($query) {
                    $query->where('request_type', 'verify');
                },
                'reports as reports_count',
            ])
            ->latest()
            ->paginate(10);

        return view('livewire.admin.projects', [
            'projects' => $projects,
        ])->layout('components.admin.layout', ['title' => 'Projects']);
    }
}

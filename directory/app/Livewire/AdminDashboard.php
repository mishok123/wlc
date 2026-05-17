<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use Livewire\WithPagination;

class AdminDashboard extends Component
{
    use WithPagination;

    public function approveProject($projectId)
    {
        $project = Project::find($projectId);
        $project->update(['list_status' => 'approved']);
        $project->calculateScores();
        session()->flash('message', 'Project approved successfully.');
    }

    public function verifyProject($projectId)
    {
        $project = Project::find($projectId);
        $project->update(['list_status' => 'verified']);
        $project->calculateScores();
        session()->flash('message', 'Project marked as verified.');
    }

    public function markAsScam($projectId)
    {
        $project = Project::find($projectId);
        $project->update(['list_status' => 'scam']);
        $project->calculateScores();
        session()->flash('message', 'Project marked as SCAM.');
    }

    public function deleteProject($projectId)
    {
        Project::find($projectId)->delete();
        session()->flash('message', 'Project deleted.');
    }

    public function render()
    {
        return view('livewire.admin-dashboard', [
            'pendingProjects' => Project::where('list_status', 'proposed')->latest()->get(),
            'allProjects' => Project::where('list_status', '!=', 'proposed')->latest()->paginate(10),
        ])->layout('components.admin.layout', [
            'title' => 'Admin Dashboard',
            'subtitle' => 'Moderation & project management',
        ]);
    }
}
